# syntax=docker/dockerfile:1
# Nuxt 3 (SSR, Nitro node-server) untuk Cloud Run — base runtime nodejs24.

# ---- build stage ----
FROM node:24-alpine AS build
WORKDIR /app
# Install deps deterministik dari package-lock.json (lockfileVersion 3).
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
# Salin sumber & build → menghasilkan .output/ (server Nitro self-contained).
COPY . .
RUN npm run build

# ---- runtime stage ----
FROM node:24-alpine AS runtime
WORKDIR /app
ENV NODE_ENV=production
# Cloud Run menyuntik PORT=8080; Nitro node-server menghormati PORT/HOST.
ENV HOST=0.0.0.0
ENV PORT=8080
# .output sudah membundel dependency runtime — tak perlu node_modules.
COPY --from=build /app/.output ./.output
EXPOSE 8080
CMD ["node", ".output/server/index.mjs"]
