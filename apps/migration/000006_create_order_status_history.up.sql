CREATE TABLE IF NOT EXISTS order_status_history (
  id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
   order_id BIGINT NOT NULL,
   from_status VARCHAR(20),
   to_status VARCHAR(20) NOT NULL,
   reason TEXT,
   changed_by BIGINT,
   changed_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),

   CONSTRAINT fk_order_status_history_order_id FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
   CONSTRAINT chk_order_status_history_from CHECK(from_status IS NULL OR from_status IN('ACTIVE', 'CANCELLED', 'COMPLETED')),
   CONSTRAINT chk_order_status_history_to CHECK(to_status IN('ACTIVE', 'CANCELLED', 'COMPLETED'))
);

CREATE INDEX IF NOT EXISTS idx_order_status_history_order_id ON order_status_history(order_id);