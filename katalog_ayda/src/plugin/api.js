const API_BASE_URL = "127.0.0.1:8000"

export async function getData(path, queryParams = {}) {
    try {
        // Build base URL
        const baseUrl = `http://${API_BASE_URL}/api/${path}`;

        // Convert queryParams object → query string (if provided)
        const queryString = new URLSearchParams(queryParams).toString();
        const url = queryString ? `${baseUrl}?${queryString}` : baseUrl;

        // Fetch data
        const res = await fetch(url);

        // Error handling
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }

        // Optional debug log
        console.log("Fetch URL:", url);
        const data = await res.json();
        console.log("Data produk:", data);

        return data;

    } catch (error) {
        console.error("Fetch failed:", error);
        throw error;
    }

    

}

// export async function submitForm(path, value) {
//     try {
//       const res = await fetch(`http://${API_BASE_URL}/api/${path}`, {
//         method: "POST",
//         headers: {
//           "Content-Type": "application/json",
//         },
//         body: JSON.stringify(value),
//       })
  
//       if (!res.ok) {
//         throw new Error(`HTTP Error: ${res.status}`)
//       }
  
//       const data = await res.json()
//      return data
//     } catch (error) {
//       console.error("Gagal mengirim data:", error)
//     }
//   }

  export async function submitFormMultipart(path, value) {
    try {
      const res = await fetch(`http://${API_BASE_URL}/api/${path}`, {
        method: "POST",
        body: value,
      })
  
      if (!res.ok) {
        throw new Error(`HTTP Error: ${res.status}`)
      }
  
      const data = await res.json()
     return data
    } catch (error) {
      console.error("Gagal mengirim data:", error)
    }
  }

