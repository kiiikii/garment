package main

import (
	"bytes"
	"encoding/json"
	"net/http"
	"net/http/httptest"
	"strings"
	"testing"
)

func TestHealthCheck(t *testing.T) {
	router := buildRouter(nil)

	req := httptest.NewRequest("GET", "/health", nil)
	recorder := httptest.NewRecorder()

	router.ServeHTTP(recorder, req)

	if recorder.Code != http.StatusOK {
		t.Fatalf("expected status %d, got %d", http.StatusOK, recorder.Code)
	}

	contentType := recorder.Header().Get("Content-Type")
	if !strings.Contains(contentType, "application/json") {
		t.Errorf("expected JSON content-type header, got %q", contentType)
	}

	var resp HealthResponse
	err := json.NewDecoder(recorder.Body).Decode(&resp)
	if err != nil {
		t.Fatalf("failed to decode health checkpoint response: %v", err)
	}

	if resp.Status != "ok" {
		t.Errorf("expected status payload field to be 'ok', got %q", resp.Status)
	}
}

func TestCreateOrder_Success(t *testing.T) {
	router := buildRouter(nil)

	inputData := map[string]any{
		"customer_name":    "John Doe",
		"customer_phone":   "123-456-789",
		"customer_address": "123 Garment Way",
		"production_type":  "CMT",
		"size_quantities":  map[string]int{"S": 10, "M": 20},
	}
	bodyBytes, _ := json.Marshal(inputData)

	req := httptest.NewRequest("POST", "/api/v1/orders", bytes.NewBuffer(bodyBytes))
	recorder := httptest.NewRecorder()

	router.ServeHTTP(recorder, req)

	if recorder.Code != http.StatusCreated {
		t.Errorf("expected status %d, got %d", http.StatusCreated, recorder.Code)
	}

	var resp APIResponse
	if err := json.NewDecoder(recorder.Body).Decode(&resp); err != nil {
		t.Fatalf("failed to decode response envelope: %v", err)
	}

	if !resp.Success {
		t.Errorf("expected success to be true, got false")
	}

	if resp.Error != nil {
		t.Errorf("expected error object to be null, got %v", resp.Error)
	}
}

func TestCreateOrder_MalformedJSON(t *testing.T) {
	router := buildRouter(nil)

	badJSON := []byte(`{"customer_name": "Broken JSON`)

	req := httptest.NewRequest("POST", "/api/v1/orders", bytes.NewBuffer(badJSON))
	recorder := httptest.NewRecorder()

	router.ServeHTTP(recorder, req)

	if recorder.Code != http.StatusBadRequest {
		t.Errorf("expected status %d, got %d", http.StatusBadRequest, recorder.Code)
	}

	var resp APIResponse
	if err := json.NewDecoder(recorder.Body).Decode(&resp); err != nil {
		t.Fatalf("failed to decode error response: %v", err)
	}

	if resp.Success {
		t.Errorf("expected success to be false, got true")
	}

	if resp.Error == nil {
		t.Fatal("expected error object, got nil")
	}

	if resp.Error.Code != "MALFORMED_JSON" {
		t.Errorf("expected error code 'MALFORMED_JSON', got %q", resp.Error.Code)
	}
}

func TestCreateOrder_ValidationError(t *testing.T) {
	router := buildRouter(nil)

	invalidData := map[string]any{
		"customer_name":    "Jane Doe",
		"customer_phone":   "987-654-321",
		"customer_address": "456 Stitch Ave",
		"production_type":  "INVALID_TYPE",
		"size_quantities":  map[string]int{"L": 5},
	}
	bodyBytes, _ := json.Marshal(invalidData)

	req := httptest.NewRequest("POST", "/api/v1/orders", bytes.NewBuffer(bodyBytes))
	recorder := httptest.NewRecorder()

	router.ServeHTTP(recorder, req)

	if recorder.Code != http.StatusUnprocessableEntity {
		t.Errorf("expected status %d, got %d", http.StatusUnprocessableEntity, recorder.Code)
	}

	var resp APIResponse
	if err := json.NewDecoder(recorder.Body).Decode(&resp); err != nil {
		t.Fatalf("failed to decode error response: %v", err)
	}

	if resp.Error == nil {
		t.Fatal("expected error object, got nil")
	}

	if resp.Error.Code != "VALIDATION_ERROR" {
		t.Errorf("expected error code 'VALIDATION_ERROR', got %q", resp.Error.Code)
	}

	if len(resp.Error.Details) == 0 || !strings.Contains(resp.Error.Details[0], "production_type must be strictly") {
		t.Errorf("expected validation detail message, got %v", resp.Error.Details)
	}
}

func TestGetByID(t *testing.T) {
	router := buildRouter(nil)

	inputData := map[string]any{
		"customer_name":    "Alice",
		"customer_phone":   "555-1234",
		"customer_address": "789 Pattern Rd",
		"production_type":  "FOB",
		"size_quantities":  map[string]int{"M": 10},
	}
	bodyBytes, _ := json.Marshal(inputData)
	postReq := httptest.NewRequest("POST", "/api/v1/orders", bytes.NewBuffer(bodyBytes))
	postRecorder := httptest.NewRecorder()
	router.ServeHTTP(postRecorder, postReq)

	if postRecorder.Code != http.StatusCreated {
		t.Fatalf("expected status %d, got %d", http.StatusCreated, postRecorder.Code)
	}

	getReq := httptest.NewRequest("GET", "/api/v1/orders/ord_1", nil)
	getRecorder := httptest.NewRecorder()
	router.ServeHTTP(getRecorder, getReq)

	if getRecorder.Code != http.StatusOK {
		t.Errorf("expected status %d, got %d", http.StatusOK, getRecorder.Code)
	}

	var successResp APIResponse
	if err := json.NewDecoder(getRecorder.Body).Decode(&successResp); err != nil {
		t.Fatalf("failed to decode response payload: %v", err)
	}

	if !successResp.Success {
		t.Errorf("expected success to be true, got false")
	}

	orderData, ok := successResp.Data.(map[string]any)

	if !ok {
		t.Fatalf("expected data to be an order object, got %T", successResp.Data)
	}

	if orderData["id"] != "ord_1" {
		t.Errorf("expected auto-incremented order identifier 'ord_1', got %v", orderData["id"])
	}

	if orderData["customer_name"] != "Alice" {
		t.Errorf("expected persisted customer name string field 'Alice', got %v", orderData["customer_name"])
	}

	if orderData["production_type"] != "FOB" {
		t.Errorf("expected production style type field value 'FOB', got %v", orderData["production_type"])
	}

	missingReq := httptest.NewRequest("GET", "/api/v1/orders/ord_999", nil)
	missingRecorder := httptest.NewRecorder()
	router.ServeHTTP(missingRecorder, missingReq)

	if missingRecorder.Code != http.StatusNotFound {
		t.Errorf("expected status %d, got %d", http.StatusNotFound, missingRecorder.Code)
	}

	var errorResp APIResponse
	if err := json.NewDecoder(missingRecorder.Body).Decode(&errorResp); err != nil {
		t.Fatalf("failed to decode error response: %v", err)
	}

	if errorResp.Error == nil {
		t.Fatal("expected error object, got nil")
	}

	if errorResp.Error.Code != "NOT_FOUND" {
		t.Errorf("expected error code 'NOT_FOUND', got %q", errorResp.Error.Code)
	}
}

func TestGetAllOrders(t *testing.T) {
	router := buildRouter(nil)

	order1 := map[string]any{
		"customer_name": "Company A", "customer_phone": "111", "customer_address": "Addr A",
		"production_type": "CMT", "size_quantities": map[string]int{"L": 5},
	}
	order2 := map[string]any{
		"customer_name": "Company B", "customer_phone": "222", "customer_address": "Addr B",
		"production_type": "FOB", "size_quantities": map[string]int{"S": 12},
	}

	for i, orderData := range []map[string]any{order1, order2} {
		b, err := json.Marshal(orderData)
		if err != nil {
			t.Fatalf("failed to marshal mock seed data at index %d: %v", i, err)
		}

		rec := httptest.NewRecorder()
		router.ServeHTTP(rec, httptest.NewRequest("POST", "/api/v1/orders", bytes.NewBuffer(b)))
		if rec.Code != http.StatusCreated {
			t.Fatalf("failed to seed order data at index %d: expected status 201, got %d", i, rec.Code)
		}
	}

	req := httptest.NewRequest("GET", "/api/v1/orders", nil)
	recorder := httptest.NewRecorder()
	router.ServeHTTP(recorder, req)

	if recorder.Code != http.StatusOK {
		t.Fatalf("expected status %d, got %d", http.StatusOK, recorder.Code)
	}

	var resp APIResponse
	if err := json.NewDecoder(recorder.Body).Decode(&resp); err != nil {
		t.Fatalf("failed to decode array payload response wrapper: %v", err)
	}

	if !resp.Success {
		t.Errorf("expected resp.Success to be true, got false")
	}

	ordersSlice, ok := resp.Data.([]any)
	if !ok {
		t.Fatalf("expected data block to contain a slice list array, got %T", resp.Data)
	}

	if len(ordersSlice) != 2 {
		t.Errorf("expected exactly 2 items in database collection array slice, got %d", len(ordersSlice))
	}

	foundCompA := false
	foundCompB := false

	for _, item := range ordersSlice {
		orderMap, ok := item.(map[string]any)
		if !ok {
			t.Fatalf("expected individual array element to be an object map, got %T", item)
		}

		customerName, _ := orderMap["customer_name"].(string)

		switch customerName {
		case "Company A":
			foundCompA = true
		case "Company B":
			foundCompB = true
		}
	}

	if !foundCompA {
		t.Errorf("expected to find 'Company A' inside the retrieved orders array, but it was missing")
	}

	if !foundCompB {
		t.Errorf("expected to find 'Company B' inside the retrieved orders array, but it was missing")
	}
}
