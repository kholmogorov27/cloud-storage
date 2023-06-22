window.FileThumbnail = class FileThumbnail {
  constructor(src, fallbackSrc="assets/fallbackFileThumbnail.png") {
    // container
    const containerEl = document.createElement('div')
    containerEl.classList.add('filecard-thumbnail')

    // thumbnail
    const thumbnailEl = document.createElement('img')
    thumbnailEl.src = 'uploads/thumbnails' + file.path
    thumbnailEl.addEventListener('error', e => {
      const path = fallbackSrc
      if (e.target.src !== path) {
        e.target.src = path
      }
    })

    // inserting the thumbnail into the container 
    containerEl.append(thumbnailEl)			

    this = containerEl
  }
}