const API_BASE_URL = 'http://127.0.0.1:8000';

// Fungsi bantu untuk bikin query params
function buildQueryParams(params) {
    const query = new URLSearchParams(params).toString();
    return query ? `?${query}` : '';
  }


  // Fungsi GET data ALL
  export async function getData(queryParams = {}) {
    const url = `${API_BASE_URL}/api/produks${buildQueryParams(queryParams)}`;
    const res = await fetch(url);
    if (!res.ok) {
      throw new Error(`GET Error: ${res.status}`);
    }
    return res.json();
  }

  // Fungsi GET data BY ID
  export async function getDataByID(id, queryParams = {}) {
    const url = `${API_BASE_URL}/api/produks/${id}${buildQueryParams(queryParams)}`;
    const res = await fetch(url);
    if (!res.ok) {
      throw new Error(`GET Error: ${res.status}`);
    }
    return res.json();
  }


  // Fungsi POST data
  export async function postProduk(pathOrFormData, formDataMaybe) {
    let path = 'produks'
    let data = null
    if (formDataMaybe === undefined && pathOrFormData instanceof FormData) {
      data = pathOrFormData
    } else {
      path = pathOrFormData
      data = formDataMaybe
    }
  
    const url = `${API_BASE_URL}/api/${path}`
  
    const options = {
      method: 'POST',
      body: data 
    }
  
    const res = await fetch(url, options)
    if (!res.ok) throw new Error(`POST Error: ${res.status}`)
    return res.json()
  }


  // Fungsi PUT data
  export async function updateProduk(id, data, queryParams = {}) {
    const url = `${API_BASE_URL}/api/produks/${id}${buildQueryParams(queryParams)}`
  
    const headers = { Accept: 'application/json' }
  
    if (data instanceof FormData) {
      data.append('_method', 'PUT') // Laravel: POST + _method=PUT
      const res = await fetch(url, { method: 'POST', body: data, headers })
      if (!res.ok) throw new Error(`PUT Error: ${res.status}`)
      return res.json()
    } else {
      const res = await fetch(url, {
        method: 'PUT',
        headers: { ...headers, 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      })
      if (!res.ok) throw new Error(`PUT Error: ${res.status}`)
      return res.json()
    }
  }

  // Fungsi DELETE data
  export async function deleteData(id) {
    const url = `${API_BASE_URL}/api/produks/${id}`
    const res = await fetch(url, { method: 'DELETE', headers: { Accept: 'application/json' } })
    if (!res.ok) throw new Error(`DELETE Error: ${res.status}`)
    return res.ok
  }
  
  /* ---------- helper to compose full image URL ---------- */
  export function fullImageUrl(path) {
    if (!path) return null
    // backend menyimpan path relatif: 'temp/xxx.png' atau 'gambar_produk/xxx.png'
    return `${API_BASE_URL}/storage/${path}`
  }
  
  // ---------- END API PRODUK ---------------------------------------------------------------------------

  // API FASILITAS

  export async function getFasilitas(queryParams = {}) {
    const url = `${API_BASE_URL}/api/fasilitas${buildQueryParams(queryParams)}`;
    const res = await fetch(url);
    if (!res.ok) {
      throw new Error(`GET Error: ${res.status}`);
    }
    return res.json();
  }
  
  export async function postFasilitas(data) {
    const res = await fetch(`${API_BASE_URL}/api/fasilitas`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    })
    if (!res.ok) throw new Error('Gagal tambah fasilitas')
    return await res.json()
  }
  
  export async function deleteFasilitas(id) {
    const res = await fetch(`${API_BASE_URL}/fasilitas/${id}`, { method: 'DELETE' })
    if (!res.ok) throw new Error('Gagal hapus fasilitas')
    return await res.json()
  }