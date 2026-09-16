(function () {
  const proofContainer = document.querySelector('.proof-grid');
  const gameplayContainer = document.querySelector('.gameplay-list');
  const container = proofContainer || gameplayContainer;
  if (!container) return;
  const kind = proofContainer ? 'proof' : 'gameplay';

  function isVideo(url) {
    return /\.(mp4|webm|ogg|mov)(?:[?#]|$)/i.test(url);
  }

  function showMedia(item) {
    const lightbox = document.getElementById('lightbox');
    const content = document.getElementById('lightboxContent');
    if (!lightbox || !content) return;
    content.replaceChildren();
    const media = document.createElement(isVideo(item.media_url) ? 'video' : 'img');
    media.src = item.media_url;
    if (media.tagName === 'VIDEO') {
      media.controls = true;
      media.autoplay = true;
      media.playsInline = true;
    } else {
      media.alt = item.title;
    }
    content.append(media);
    lightbox.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function renderProof(item) {
    const card = document.createElement('div');
    card.className = 'glass-card proof-card';
    const mediaBox = document.createElement('div');
    mediaBox.className = 'proof-media';
    mediaBox.addEventListener('click', () => showMedia(item));
    const media = document.createElement(isVideo(item.media_url) ? 'video' : 'img');
    media.src = item.thumbnail_url || item.media_url;
    if (media.tagName === 'VIDEO') {
      media.muted = true;
      media.playsInline = true;
      media.preload = 'metadata';
    } else {
      media.alt = item.title;
      media.loading = 'lazy';
    }
    mediaBox.append(media);
    if (isVideo(item.media_url)) {
      const play = document.createElement('div');
      play.className = 'play-overlay';
      play.innerHTML = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>';
      mediaBox.append(play);
    }
    const footer = document.createElement('div');
    footer.className = 'proof-footer';
    const row = document.createElement('div');
    row.className = 'verified-row';
    const verified = document.createElement('span');
    verified.className = 'verified-tag';
    verified.textContent = '✓ Verified';
    const platform = document.createElement('span');
    platform.className = 'platform-tag';
    platform.textContent = item.platform || 'Android';
    row.append(verified, platform);
    footer.append(row);
    if (item.title) {
      const caption = document.createElement('div');
      caption.className = 'caption';
      caption.textContent = item.title;
      footer.append(caption);
    }
    card.append(mediaBox, footer);
    return card;
  }

  function renderGameplay(item) {
    const card = document.createElement('div');
    card.className = 'gameplay-card glass-card';
    const thumb = document.createElement('div');
    thumb.className = 'gameplay-thumb';
    thumb.addEventListener('click', () => showMedia(item));
    if (item.thumbnail_url) {
      const img = document.createElement('img');
      img.src = item.thumbnail_url;
      img.alt = item.title;
      img.loading = 'lazy';
      thumb.append(img);
    } else {
      const video = document.createElement('video');
      video.src = item.media_url;
      video.muted = true;
      video.preload = 'metadata';
      thumb.append(video);
    }
    const play = document.createElement('div');
    play.className = 'play-circle';
    play.innerHTML = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>';
    const platform = document.createElement('span');
    platform.className = 'platform-pill';
    platform.textContent = item.platform || 'Android';
    thumb.append(play, platform);
    const info = document.createElement('div');
    info.className = 'gameplay-info';
    const title = document.createElement('h4');
    title.textContent = item.title;
    const description = document.createElement('p');
    description.textContent = item.description || '';
    info.append(title, description);
    card.append(thumb, info);
    return card;
  }

  fetch('/api/gallery?kind=' + kind, { cache: 'no-store' })
    .then(async response => {
      const body = await response.json();
      if (!response.ok || !body.success) throw new Error(body.message || 'Gallery unavailable');
      container.replaceChildren();
      for (const item of body.items) container.append(kind === 'proof' ? renderProof(item) : renderGameplay(item));
      if (!body.items.length) container.textContent = 'No media available yet.';
    })
    .catch(() => { container.textContent = 'Gallery could not be loaded. Please refresh the page.'; });
})();
