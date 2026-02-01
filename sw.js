self.addEventListener('install', e => {
  e.waitUntil(
    caches.open('wp-clean').then(cache => {
      return cache.addAll(['index.php']);
    })
  );
});