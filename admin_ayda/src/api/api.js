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
  export async function getData(id, queryParams = {}) {
    const url = `${API_BASE_URL}/api/produks/${id}${buildQueryParams(queryParams)}`;
    const res = await fetch(url);
    if (!res.ok) {
      throw new Error(`GET Error: ${res.status}`);
    }
    return res.json();
  }


  // Fungsi POST data
  export async function postProduk(path, data, queryParams = {}) {
    const url = `${API_BASE_URL}/api/${path}${buildQueryParams(queryParams)}`
    let options = { method: 'POST' }
  
    // Jika data adalah FormData (upload file)
    if (data instanceof FormData) {
      options.body = data
    } else {
      // Jika data biasa (JSON)
      options.headers = { 'Content-Type': 'application/json' }
      options.body = JSON.stringify(data)
    }
  
    const res = await fetch(url, options)
    if (!res.ok) throw new Error(`POST Error: ${res.status}`)
    return res.json()
  }


  // Fungsi PUT data
  export async function updateProduk(id, data, queryParams = {}) {
    const url = `${API_BASE_URL}/api/produks/${id}${buildQueryParams(queryParams)}`;

    const headers = { Accept: 'application/json' };

    if (data instanceof FormData) {
      data.append('_method', 'PUT')
      const res = await fetch(url, {
        method: 'POST', // Laravel accepts post+_method=PUT for multipart
        headers, 
        body: data
      });
      if (!res.ok) throw new Error(`PUT Error: ${res.status}`);
      return res.json();
    } else {
      const res = await fetch(url, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
      });
      if (!res.ok) throw new Error(`PUT Error: ${res.status}`);
      return res.json();
    
      }
  }

  // Fungsi DELETE data
  export async function deleteData(path, queryParams = {}) {
    const url = `${API_BASE_URL}/api/${path}${buildQueryParams(queryParams)}`;
    const res = await fetch(url, { method: 'DELETE' });
    if (!res.ok && res.status !== 204) throw new Error(`DELETE Error: ${res.status}`);
    
    if (res.status === 204) return null;
    return res.json();
  }