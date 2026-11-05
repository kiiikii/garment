package main

import (
	"context"
	"errors"
	"fmt"
	"os/signal"
	"strings"
	"sync"
	"syscall"
	"time"

	"github.com/joho/godotenv"

	"encoding/json"
	"log"
	"net/http"
	"os"
)

type HealthResponse struct {
	Status string `json:"status"`
}

type InMemoryOrderStore struct {
	mu     sync.RWMutex
	orders map[string]Order
	nextID int
}

type Order struct {
	ID              string         `json:"id"`
	CustomerName    string         `json:"customer_name"`
	CustomerPhone   string         `json:"customer_phone"`
	CustomerAddress string         `json:"customer_address"`
	ProductionType  string         `json:"production_type"`
	SizeQuantities  map[string]int `json:"size_quantities"`
}

type CreateOrderRequest struct {
	CustomerName    string         `json:"customer_name"`
	CustomerPhone   string         `json:"customer_phone"`
	CustomerAddress string         `json:"customer_address"`
	ProductionType  string         `json:"production_type"`
	SizeQuantities  map[string]int `json:"size_quantities"`
}

type APIResponse struct {
	Success bool      `json:"success"`
	Data    any       `json:"data"`
	Error   *APIError `json:"error"`
}

type APIError struct {
	Code    string   `json:"code"`
	Message string   `json:"message"`
	Details []string `json:"details,omitempty"`
}

func NewInMemoryOrderStore() *InMemoryOrderStore {
	return &InMemoryOrderStore{
		orders: make(map[string]Order),
	}
}

func (s *InMemoryOrderStore) Create(req CreateOrderRequest) Order {
	//! Lock
	s.mu.Lock()
	defer s.mu.Unlock()

	//! Generate ID
	s.nextID++
	currentID := fmt.Sprintf("ord_%d", s.nextID)

	sized := make(map[string]int)
	for size, qty := range req.SizeQuantities {
		sized[size] = qty
	}

	//! Make order from request
	newOrder := Order{
		ID:              currentID,
		CustomerName:    req.CustomerName,
		CustomerPhone:   req.CustomerPhone,
		CustomerAddress: req.CustomerAddress,
		ProductionType:  req.ProductionType,
		SizeQuantities:  sized,
	}

	//! Saving on Map
	s.orders[currentID] = newOrder

	//! Return Order
	return newOrder
}

func (s *InMemoryOrderStore) GetByID(id string) (Order, bool) {
	s.mu.RLock()
	defer s.mu.RUnlock()

	order, exist := s.orders[id]

	return order, exist
}

func (s *InMemoryOrderStore) GetAll() []Order {
	s.mu.RLock()
	defer s.mu.RUnlock()

	orders := make([]Order, 0, len(s.orders))

	for _, ordrs := range s.orders {
		orders = append(orders, ordrs)
	}

	return orders
}

func (r CreateOrderRequest) Validate() error {
	if strings.TrimSpace(r.CustomerName) == "" {
		return errors.New("Customer_name must not be empty or consist only of space")
	}

	if strings.TrimSpace(r.CustomerPhone) == "" {
		return errors.New("Customer_phone must not be empty or consist only of space")
	}

	if strings.TrimSpace(r.CustomerAddress) == "" {
		return errors.New("Customer_address must not be empty or consist only of space")
	}

	if r.ProductionType != "CMT" && r.ProductionType != "FOB" {
		return fmt.Errorf("production_type must be strictly 'CMT' or 'FOB', got %q", r.ProductionType)
	}

	for size, quantity := range r.SizeQuantities {
		switch size {
		case "S", "M", "L", "XL":

		default:
			return fmt.Errorf("size %q is completely unsupported by manufacturing", size)
		}

		if quantity < 0 {
			return fmt.Errorf("size %q quantity cannot be negative, got %d", size, quantity)
		}
	}
	return nil
}

func writeJSON(w http.ResponseWriter, status int, data any) {
	w.Header().Set("Content-Type", "application/json")
	w.WriteHeader(status)

	SuccessPayload := APIResponse{
		Success: true,
		Data:    data,
		Error:   nil,
	}
	json.NewEncoder(w).Encode(SuccessPayload)
}

func writeError(w http.ResponseWriter, status int, code, message string, details []string) {
	w.Header().Set("Content-Type", "application/json")
	w.WriteHeader(status)

	errPayload := APIResponse{
		Success: false,
		Data:    nil,
		Error: &APIError{
			Code:    code,
			Message: message,
			Details: details,
		},
	}
	json.NewEncoder(w).Encode(errPayload)
}

func buildRouter() *http.ServeMux {
	store := NewInMemoryOrderStore()

	router := http.NewServeMux()

	router.HandleFunc("GET /health", func(w http.ResponseWriter, r *http.Request) {
		w.Header().Set("Content-Type", "application/json")
		w.WriteHeader(http.StatusOK)

		response := HealthResponse{Status: "ok"}
		if err := json.NewEncoder(w).Encode(response); err != nil {
			log.Printf("error encoding health response: %v", err)
		}
	})

	router.HandleFunc("POST /api/v1/orders", func(w http.ResponseWriter, r *http.Request) {
		var req CreateOrderRequest

		err := json.NewDecoder(r.Body).Decode(&req)
		if err != nil {
			writeError(w, http.StatusBadRequest, "MALFORMED_JSON", "Request body contains malformed JSON", nil)
			return
		}

		if err := req.Validate(); err != nil {
			details := []string{err.Error()}
			writeError(w, http.StatusUnprocessableEntity, "VALIDATION_ERROR", "The provided payload failed payload criteria rules", details)
			return
		}

		newOrder := store.Create(req)

		writeJSON(w, http.StatusCreated, newOrder)
	})

	router.HandleFunc("GET /api/v1/orders", func(w http.ResponseWriter, r *http.Request) {
		orders := store.GetAll()
		writeJSON(w, http.StatusOK, orders)
	})

	router.HandleFunc("GET /api/v1/orders/{id}", func(w http.ResponseWriter, r *http.Request) {
		id := r.PathValue("id")

		order, found := store.GetByID(id)

		if !found {
			writeError(w, http.StatusNotFound, "NOT_FOUND", "Order Not Found", nil)
			return
		}

		writeJSON(w, http.StatusOK, order)
	})
	return router
}

func main() {
	err := godotenv.Load()
	if err != nil {
		log.Println("No .env file found, using system default")
	}

	port := os.Getenv("PORT")
	if port == "" {
		port = "8080"
	}

	healthServer := &http.Server{
		Addr:    ":" + port,
		Handler: buildRouter(),
	}

	shutdownSignalCtx, stopSignal := signal.NotifyContext(
		context.Background(),
		os.Interrupt,
		syscall.SIGTERM,
	)

	defer stopSignal()

	go func() {
		log.Printf("pipeline up on %s", healthServer.Addr)
		err := healthServer.ListenAndServe()
		if err != nil && !errors.Is(err, http.ErrServerClosed) {
			log.Fatalf("Fatal Network crash: %v", err)
		}
	}()

	<-shutdownSignalCtx.Done()

	log.Println("Intercept termintaion signal. Initiating systematic teardown...")

	timeoutCtx, cancelTimeout := context.WithTimeout(context.Background(), 5*time.Second)
	defer cancelTimeout()

	if err := healthServer.Shutdown(timeoutCtx); err != nil {
		log.Printf("Server was force to kill active connections: %v", err)
	}

	log.Println("Server has cleanly exited. Goodbye.")
}
