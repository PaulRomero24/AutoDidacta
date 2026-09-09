const API_URL = 'https://marisa-backend.onrender.com'

const PHP_URL = 'https://marisaberdu.com/api/contacto.php'

export const enviarConsultaEstudiantil = async (datos) => {
    const response = await fetch(PHP_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            asunto: `Consulta estudiantil — ${datos.institucion}`,
            cuerpo: `
                <h2>Nueva consulta estudiantil</h2>
                <p><b>Institución:</b> ${datos.institucion}</p>
                <p><b>Responsable:</b> ${datos.responsable}</p>
                <p><b>Email:</b> ${datos.email}</p>
                <p><b>Teléfono:</b> ${datos.telefono}</p>
                <p><b>Cantidad de personas:</b> ${datos.cantidad_alumnos}</p>
                <p><b>Nivel educativo:</b> ${datos.nivel_educativo}</p>
                <p><b>Fecha estimada:</b> ${datos.fecha_estimada}</p>
                <p><b>Mensaje:</b> ${datos.mensaje}</p>
            `
        })
    })
    if (!response.ok) throw new Error('Error al enviar')
    return response.json()
}

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

