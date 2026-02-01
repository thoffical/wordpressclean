<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="manifest" href="manifest.json">
<script>
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('sw.js');
}

async function loadData(type) {
  let map = {
    posts: "wp/v2/posts",
    pages: "wp/v2/pages",
    plugins: "wp/v2/plugins",
    themes: "wp/v2/themes"
  };

  let res = await fetch("api.php?endpoint=" + map[type]);
  let data = await res.json();

  let out = "<h2>" + type.toUpperCase() + "</h2>";
  data.forEach(item => {
    out += `<div style="padding:10px;border-bottom:1px solid #ccc">
              ${item.name || item.title.rendered}
            </div>`;
  });

  document.getElementById("content").innerHTML = out;
}
</script>
<style>
body { font-family: sans-serif; margin:0; }
nav { display:flex; gap:10px; padding:10px; background:#111; color:white; }
button { padding:10px; }
</style>
</head>
<body>

<nav>
  <button onclick="loadData('posts')">Posts</button>
  <button onclick="loadData('pages')">Pages</button>
  <button onclick="loadData('plugins')">Plugins</button>
  <button onclick="loadData('themes')">Themes</button>
</nav>

<div id="content" style="padding:15px;">Select a section</div>

</body>
</html>
