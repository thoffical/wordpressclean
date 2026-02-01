self.addEventListener('install', e => {
  e.waitUntil(
    caches.open('wp-admin').then(cache => {
      return cache.addAll(['index.php']);
    })
  );
});