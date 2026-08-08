const API_URL = 'https://marisa-backend-production.up.railway.app'

// ← agregá estas funciones nuevas

export const loginAdmin = async (username, password) => {
    const formData = new FormData()
    formData.append('username', username)
    formData.append('password', password)

    const response = await fetch(`${API_URL}/auth/login`, {
        method: 'POST',
        body: formData
    })

    if (!response.ok) throw new Error('Credenciales incorrectas')
    return response.json()
}

export const getFotosPendientes = async (token) => {
    const response = await fetch(`${API_URL}/fotos/pendientes`, {
        headers: { 'Authorization': `Bearer ${token}` }
    })
    return response.json()
}

export const aprobarFoto = async (id, token) => {
    const response = await fetch(`${API_URL}/fotos/aprobar/${id}`, {
        method: 'PUT',
        headers: { 'Authorization': `Bearer ${token}` }
    })
    return response.json()
}

export const rechazarFoto = async (id, token) => {
    const response = await fetch(`${API_URL}/fotos/rechazar/${id}`, {
        method: 'PUT',
        headers: { 'Authorization': `Bearer ${token}` }
    })
    return response.json()
}

export const subirFoto = async (formData) => {
    const response = await fetch(`${API_URL}/fotos/subir`, {
        method: 'POST',
        body: formData
    })
    return response.json()
}

export const getFotosAprobadas = async () => {
    const response = await fetch(`${API_URL}/fotos/aprobadas`)
    return response.json()
}

export const getFotosAprobadasAdmin = async (token) => {
    const response = await fetch(`${API_URL}/fotos/aprobadas`, {
        headers: { 'Authorization': `Bearer ${token}` }
    })
    return response.json()
}

export const eliminarFoto = async (id, token) => {
    const response = await fetch(`${API_URL}/fotos/eliminar/${id}`, {
        method: 'DELETE',
        headers: { 'Authorization': `Bearer ${token}` }
    })
    return response.json()
}


export const enviarConsulta = async (datos) => {
    const response = await fetch(`${API_URL}/contacto/estudiantiles`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    })
    if (!response.ok) throw new Error('Error al enviar')
    return response.json()
}