window.fetchWithReload = function (path, options) {
  fetch(path, options).then(() => location.reload())
}