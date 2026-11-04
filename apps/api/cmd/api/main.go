package main

import (
	"context"
	"errors"
	"os/signal"
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

func buildRouter() *http.ServeMux {
	router := http.NewServeMux()

	router.HandleFunc("GET /health", func(w http.ResponseWriter, r *http.Request) {
		w.Header().Set("Content-Type", "application/json")
		w.WriteHeader(http.StatusOK)

		response := HealthResponse{Status: "ok"}
		if err := json.NewEncoder(w).Encode(response); err != nil {
			log.Printf("error encoding health response: %v", err)
		}
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
