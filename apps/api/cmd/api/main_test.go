package main

import (
	"encoding/json"
	"net/http"
	"net/http/httptest"
	"strings"
	"testing"
)

func TestSystemEndpoint(t *testing.T) {
	router := buildRouter()

	req := httptest.NewRequest("GET", "/health", nil)

	recorder := httptest.NewRecorder()

	router.ServeHTTP(recorder, req)
	if recorder.Code != http.StatusOK {
		t.Errorf("Expected status %d, got %d", http.StatusOK, recorder.Code)
	}

	contentType := recorder.Header().Get("Content-Type")
	if !strings.Contains(contentType, "application/json") {
		t.Errorf("Expected JSON content-type hedaer, got %q", contentType)
	}

	var actualPayload HealthResponse
	err := json.NewDecoder(recorder.Body).Decode(&actualPayload)
	if err != nil {
		t.Fatalf("Failed to decode response payload into tergeted structure: %v", err)
	}

	if actualPayload.Status != "ok" {
		t.Errorf("expected payload structural field 'Alive' to be true, got false")
	}
}
