# syntax=docker/dockerfile:1

# ---- build stage ----
FROM golang:1.26-alpine AS build
WORKDIR /src
COPY go.mod go.sum ./
RUN go mod download
COPY . .
RUN CGO_ENABLED=0 go build -o /out/api     ./cmd/api \
 && CGO_ENABLED=0 go build -o /out/seed    ./cmd/seed \
 && CGO_ENABLED=0 go build -o /out/migrate ./cmd/migrate

# ---- runtime stage ----
FROM alpine:3.20
RUN apk add --no-cache ca-certificates    # untuk HTTPS (gateway WhatsApp)
WORKDIR /app
COPY --from=build /out/ /app/
COPY --from=build /src/migrations /app/migrations
COPY entrypoint.sh /app/entrypoint.sh
RUN chmod +x /app/entrypoint.sh
EXPOSE 8080
ENTRYPOINT ["/app/entrypoint.sh"]
