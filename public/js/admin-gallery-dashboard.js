window.loadActiveProofs = async function () {
  const container = document.getElementById('proofsMiniContainer');
  try {
    const response = await API.get('/admin/gallery?kind=proof');
    const proofs = response.items || [];
    document.getElementById('statProofsCount').textContent = proofs.length;
    container.replaceChildren();
    if (!proofs.length) {
      container.textContent = 'No proofs yet. Open the gallery manager to add one.';
      return;
    }
    for (const item of proofs) {
      const card = document.createElement('div');
      card.className = 'proof-mini-card';
      const media = document.createElement(item.thumbnail_url || !/\.(mp4|webm|ogg|mov)(?:[?#]|$)/i.test(item.media_url) ? 'img' : 'video');
      media.src = item.thumbnail_url || item.media_url;
      media.className = 'proof-mini-img';
      media.style.objectFit = 'cover';
      if (media.tagName === 'VIDEO') media.muted = true;
      const body = document.createElement('div');
      body.className = 'proof-mini-body';
      const title = document.createElement('div');
      title.className = 'proof-mini-title';
      title.textContent = item.title;
      const tag = document.createElement('div');
      tag.className = 'proof-mini-tag';
      tag.textContent = item.tag || 'Verified';
      const link = document.createElement('a');
      link.href = '/admin/proofs.html';
      link.textContent = 'Manage';
      link.style.cssText = 'font-size:0.75rem;color:var(--neon-green-bright);';
      body.append(title, tag, link);
      card.append(media, body);
      container.append(card);
    }
  } catch (err) {
    container.textContent = 'Gallery could not be loaded: ' + err.message;
  }
};
