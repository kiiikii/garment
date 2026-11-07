CREATE TABLE IF NOT EXISTS order_sizes (
  id BIGINT GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
  order_id BIGINT NOT NULL,
  size_id BIGINT NOT NULL,
  quantity INTEGER NOT NULL CHECK(quantity > 0),

  CONSTRAINT fk_order_sizes_order_id FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_order_sizes_size_id FOREIGN KEY (size_id) REFERENCES sizes(id) ON DELETE RESTRICT,
  CONSTRAINT uq_order_id_size_id UNIQUE (order_id, size_id)
);

CREATE INDEX IF NOT EXISTS idx_order_sizes_size_id ON order_sizes(size_id);