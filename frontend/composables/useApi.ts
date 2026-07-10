// Klien API tipis untuk backend Go. Menyisipkan JWT (dari cookie) & memilih base URL
// yang tepat: SSR pakai apiBaseServer (internal, cepat), klien pakai apiBase publik.
export function useApi() {
  const cfg = useRuntimeConfig()
  const token = useCookie<string | null>('budes_token', {
    sameSite: 'lax',
    maxAge: 60 * 60 * 24 * 7, // 7 hari
    path: '/',
  })

  const base = import.meta.server
    ? cfg.apiBaseServer || cfg.public.apiBase
    : cfg.public.apiBase

  // true bila API dikonfigurasi; false = mode mock (tanpa backend).
  const enabled = !!cfg.public.apiBase

  async function req<T = any>(path: string, opts: any = {}): Promise<T> {
    const headers: Record<string, string> = { ...(opts.headers || {}) }
    if (token.value) headers.Authorization = `Bearer ${token.value}`
    return await $fetch<T>(path, { baseURL: base, ...opts, headers })
  }

  // Ambil properti "data" dari amplop { data, ... } respons backend.
  async function data<T = any>(path: string, opts: any = {}): Promise<T> {
    const res = await req<{ data: T }>(path, opts)
    return (res as any)?.data as T
  }

  return { req, data, token, enabled }
}
