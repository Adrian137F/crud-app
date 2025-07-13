const API_URL = '../backend/api.php';

function cargar() {
  fetch(API_URL)
    .then(res => res.json())
    .then(data => {
      const list = document.getElementById('list');
      list.innerHTML = '';
      data.forEach(item => {
        const li = document.createElement('li');
        li.innerHTML = `
          <b>${item.name}</b>: ${item.description}
          <button onclick="editar(${item.id})">Editar</button>
          <button onclick="eliminar(${item.id})">Eliminar</button>
        `;
        list.appendChild(li);
      });
    });
}

function editar(id) {
  fetch(`${API_URL}?id=${id}`)
    .then(res => res.json())
    .then(data => {
      document.getElementById('id').value = data.id;
      document.getElementById('name').value = data.name;
      document.getElementById('description').value = data.description;
    });
}

function eliminar(id) {
  fetch(API_URL, {
    method: 'DELETE',
    body: new URLSearchParams({ id })
  }).then(() => cargar());
}

document.getElementById('form').addEventListener('submit', e => {
  e.preventDefault();
  const id = document.getElementById('id').value;
  const name = document.getElementById('name').value;
  const description = document.getElementById('description').value;
  const data = JSON.stringify({ id, name, description });

  fetch(API_URL, {
    method: id ? 'PUT' : 'POST',
    body: data
  }).then(() => {
    e.target.reset();
    cargar();
  });
});

cargar();
