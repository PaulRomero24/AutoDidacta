// Todo el backend ahora es PHP en Hostinger
const PHP_URL = "https://marisaberdu.com/api";

// Helper: hace la petición y lanza Error con el mensaje del servidor si falla.
// Si el servidor responde 401 (token vencido o inválido), el error trae status = 401.
async function pedir(ruta, opciones = {}, token = "") {
  const headers = { ...(opciones.headers || {}) };
  if (token) headers["Authorization"] = `Bearer ${token}`;

  const response = await fetch(`${PHP_URL}/${ruta}`, { ...opciones, headers });
  const data = await response.json().catch(() => ({}));

  if (!response.ok) {
    const error = new Error(data.detail || "Error en la solicitud");
    error.status = response.status;
    throw error;
  }
  return data;
}

// ===== CONTACTO =====
export const enviarConsultaEstudiantil = (datos) =>
  pedir("contacto.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
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
            `,
    }),
  });

// ===== LOGIN =====
export const loginAdmin = (username, password) => {
  const formData = new FormData();
  formData.append("username", username);
  formData.append("password", password);
  return pedir("login.php", { method: "POST", body: formData });
};

// ===== FOTOS =====
export const getFotosPendientes = (token) => pedir("pendientes.php", {}, token);

export const aprobarFoto = (id, token) =>
  pedir(`moderar.php?id=${id}&accion=aprobar`, { method: "PUT" }, token);

export const rechazarFoto = (id, token) =>
  pedir(`moderar.php?id=${id}&accion=rechazar`, { method: "PUT" }, token);

export const subirFoto = (formData) =>
  pedir("subir.php", { method: "POST", body: formData });

export const getFotosAprobadas = () => pedir("aprobadas.php");

// aprobadas.php es público, no necesita token
export const getFotosAprobadasAdmin = () => pedir("aprobadas.php");

export const eliminarFoto = (id, token) =>
  pedir(`eliminar.php?id=${id}`, { method: "DELETE" }, token);

// ===== RESEÑAS (públicas) =====
export const getResenas = (limite = 5) => pedir(`resenas.php?limite=${limite}`);

export const enviarResena = (datos) =>
  pedir("resenas.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(datos),
  });

// ===== RESEÑAS (admin) =====
export const getResenasAdmin = (estado, token) =>
  pedir(`resenas_admin.php?estado=${estado}`, {}, token);

export const moderarResena = (id, accion, token) =>
  pedir(
    `resenas_admin.php?id=${id}&accion=${accion}`,
    { method: "PUT" },
    token,
  );

export const eliminarResena = (id, token) =>
  pedir(`resenas_admin.php?id=${id}`, { method: "DELETE" }, token);
