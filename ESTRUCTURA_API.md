# Estructura API - Sistema de Inventario

## 📋 Resumen
Este sistema permite gestionar **Categorías** y **Nombres (Productos)** como listas maestras, que luego se pueden seleccionar en el inventario de productos mediante desplegables.

---

## 🗂️ Endpoints Disponibles

### **Categorías**
```
GET  /api/categorias         - Listar categorías activas
POST /api/CrearCategoria     - Crear nueva categoría
POST /api/ActualizarCategoria - Actualizar categoría
POST /api/EliminarCategoria  - Eliminar categoría (soft delete)
```

### **Nombres (Productos)**
```
GET  /api/nombres            - Listar nombres activos
POST /api/CrearNombre        - Crear nuevo nombre
POST /api/ActualizarNombre   - Actualizar nombre
POST /api/EliminarNombre     - Eliminar nombre (soft delete)
```

### **Productos (Inventario)**
```
GET  /api/productos          - Listar productos del inventario
POST /api/CrearProducto      - Crear producto en inventario
POST /api/ActualizarProducto - Actualizar producto
POST /api/EliminarProducto   - Eliminar producto (soft delete)
```

---

## 📝 Estructura de Datos

### **Categoría**
```json
{
  "id": 1,
  "categoria": "Alimentos",
  "activo": true,
  "created_at": "2025-11-14T00:00:00.000000Z",
  "updated_at": "2025-11-14T00:00:00.000000Z"
}
```

### **Nombre**
```json
{
  "id": 1,
  "nombre": "Croquetas Premium",
  "activo": true,
  "created_at": "2025-11-14T00:00:00.000000Z",
  "updated_at": "2025-11-14T00:00:00.000000Z"
}
```

### **Producto (Inventario)**
```json
{
  "id": 1,
  "categoria_id": 1,
  "nombre_id": 1,
  "categoria": "Alimentos",
  "nombre": "Croquetas Premium",
  "cantidad": 50,
  "descripcion": "Descripción del producto",
  "foto": "productos/abc123.jpg",
  "activo": true,
  "created_at": "2025-11-14T00:00:00.000000Z",
  "updated_at": "2025-11-14T00:00:00.000000Z"
}
```

---

## 🔧 Ejemplos de Uso en Frontend

### **1. Cargar Categorías y Nombres para Desplegables**

```javascript
// Cargar categorías
const fetchCategorias = async () => {
  const response = await fetch('http://127.0.0.1:8000/api/categorias');
  const data = await response.json();
  return data; // Array de categorías
};

// Cargar nombres
const fetchNombres = async () => {
  const response = await fetch('http://127.0.0.1:8000/api/nombres');
  const data = await response.json();
  return data; // Array de nombres
};
```

### **2. Crear Producto con Desplegables**

```javascript
// En tu componente de inventario
const [categorias, setCategorias] = useState([]);
const [nombres, setNombres] = useState([]);
const [categoriaSeleccionada, setCategoriaSeleccionada] = useState('');
const [nombreSeleccionado, setNombreSeleccionado] = useState('');

// Cargar opciones al montar
useEffect(() => {
  const cargarOpciones = async () => {
    const cats = await fetchCategorias();
    const noms = await fetchNombres();
    setCategorias(cats);
    setNombres(noms);
  };
  cargarOpciones();
}, []);

// Guardar producto
const crearProducto = async (formData) => {
  const response = await fetch('http://127.0.0.1:8000/api/CrearProducto', {
    method: 'POST',
    body: formData // FormData con categoria_id, nombre_id, cantidad, foto, etc.
  });
  const data = await response.json();
  return data;
};
```

### **3. Formulario de Producto con Desplegables**

```jsx
<form onSubmit={handleSubmit}>
  {/* Desplegable de Categoría */}
  <select 
    value={categoriaSeleccionada} 
    onChange={(e) => setCategoriaSeleccionada(e.target.value)}
    required
  >
    <option value="">Selecciona una categoría</option>
    {categorias.map(cat => (
      <option key={cat.id} value={cat.id}>{cat.categoria}</option>
    ))}
  </select>

  {/* Desplegable de Nombre */}
  <select 
    value={nombreSeleccionado} 
    onChange={(e) => setNombreSeleccionado(e.target.value)}
    required
  >
    <option value="">Selecciona un producto</option>
    {nombres.map(nom => (
      <option key={nom.id} value={nom.id}>{nom.nombre}</option>
    ))}
  </select>

  {/* Cantidad */}
  <input type="number" name="cantidad" required />

  {/* Descripción */}
  <textarea name="descripcion"></textarea>

  {/* Foto */}
  <input type="file" name="foto" accept="image/*" />

  <button type="submit">Guardar Producto</button>
</form>
```

### **4. Enviar Datos del Formulario**

```javascript
const handleSubmit = async (e) => {
  e.preventDefault();
  
  const formData = new FormData();
  formData.append('categoria_id', categoriaSeleccionada);
  formData.append('nombre_id', nombreSeleccionado);
  formData.append('cantidad', cantidad);
  formData.append('descripcion', descripcion);
  if (foto) formData.append('foto', foto);

  const response = await fetch('http://127.0.0.1:8000/api/CrearProducto', {
    method: 'POST',
    body: formData
  });

  if (response.ok) {
    const data = await response.json();
    console.log('Producto creado:', data);
  }
};
```

---

## 🔗 Relaciones

- **Categoría** → tiene muchos **Productos**
- **Nombre** → tiene muchos **Productos**
- **Producto** → pertenece a una **Categoría** y un **Nombre**

---

## ✅ Flujo de Trabajo Recomendado

1. **Gestionar Categorías y Nombres** en la página `/categorias`
   - Crear/Editar/Eliminar categorías
   - Crear/Editar/Eliminar nombres

2. **Inventario de Productos** en `/inventario`
   - Seleccionar categoría y nombre desde desplegables
   - Agregar cantidad, descripción y foto
   - Guardar producto con referencias a categoría y nombre

---

## 🌐 CORS Configurado

El backend acepta peticiones desde:
- `http://localhost:5173`
- `http://127.0.0.1:5173`
