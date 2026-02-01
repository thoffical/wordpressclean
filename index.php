<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="manifest" href="manifest.json">

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>

<style>
body { font-family: sans-serif; margin:0; }
nav { display:flex; gap:10px; padding:10px; background:#111; }
button { padding:10px; color:white; background:#333; border:none; }
#content { padding:15px; }
.item { padding:10px; border-bottom:1px solid #ccc; cursor:pointer; }
input { width:100%; font-size:20px; margin-bottom:10px; }
</style>

<script>
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('sw.js');
}

async function loadList(type) {
  let res = await fetch(`api.php?endpoint=wp/v2/${type}`);
  let data = await res.json();

  let out = `<h2>${type.toUpperCase()}</h2>`;
  data.forEach(item => {
    out += `<div class="item" onclick="editItem(${item.id}, '${type}')">
              ${item.title.rendered}
            </div>`;
  });

  document.getElementById("content").innerHTML = out;
}

async function editItem(id, type) {
  let res = await fetch(`api.php?endpoint=wp/v2/${type}/${id}`);
  let post = await res.json();

  document.getElementById("content").innerHTML = `
    <h2>Edit</h2>
    <input id="title" value="${post.title.rendered}">
    <textarea id="editor">${post.content.raw}</textarea>
    <br><br>
    <button onclick="saveItem(${id}, '${type}')">Save</button>
  `;

  tinymce.init({
    selector: '#editor',
    height: 400
  });
}

async function saveItem(id, type) {
  let title = document.getElementById("title").value;
  let content = tinymce.get("editor").getContent();

  await fetch(`api.php?endpoint=wp/v2/${type}/${id}`, {
    method: "POST",
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
      title: title,
      content: content
    })
  });

  alert("Saved!");
}
</script>
</head>

<body>
<nav>
  <button onclick="loadList('posts')">Posts</button>
  <button onclick="loadList('pages')">Pages</button>
</nav>

<div id="content">Select Posts or Pages</div>
</body>
</html>