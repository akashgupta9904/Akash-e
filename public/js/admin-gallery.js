let galleryItems = [];

document.addEventListener('DOMContentLoaded', () => {
  AdminApp.initLayout('proofs');
  loadProofs();
});

function currentKind() {
  return document.getElementById('galleryFilter').value;
}

function openProofModal(item) {
  document.getElementById('proofForm').reset();
  document.getElementById('proofEditId').value = item?.id || '';
  document.getElementById('proofKind').value = item?.kind || currentKind();
  document.getElementById('proofTitle').value = item?.title || '';
  document.getElementById('proofImageUrl').value = item?.media_url || '';
  document.getElementById('proofThumbnailUrl').value = item?.thumbnail_url || '';
  document.getElementById('proofPlatform').value = item?.platform || 'Android';
  document.getElementById('proofTag').value = item?.tag || 'Anti-Ban Verified';
  document.getElementById('proofDescription').value = item?.description || '';
  document.getElementById('proofModalTitle').textContent = item ? 'Edit Website Media' : 'Add Website Media';
  AdminApp.openModal('proofModal');
}

function closeProofModal() {
  AdminApp.closeModal('proofModal');
}

function editKey() {
  if (location.hostname === 'localhost' || location.hostname === '127.0.0.1') return '';
  let key = sessionStorage.getItem('gallery_edit_key');
  if (!key) {
    key = prompt('Enter the gallery edit key set in Vercel settings:');
    if (!key) throw new Error('Gallery edit key is required.');
    sessionStorage.setItem('gallery_edit_key', key);
  }
  return key;
}

async function galleryWrite(path, method, body) {
  const key = editKey();
  try {
    return await API.request(path, {
      method,
      headers: { 'x-gallery-edit-key': key },
      ...(body ? { body: JSON.stringify(body) } : {})
    });
  } catch (err) {
    if (/edit key is incorrect/i.test(err.message)) sessionStorage.removeItem('gallery_edit_key');
    throw err;
  }
}

async function loadProofs() {
  const loading = document.getElementById('proofsLoading');
  const empty = document.getElementById('proofsEmpty');
  const grid = document.getElementById('proofsGrid');
  loading.style.display = 'block';
  loading.textContent = 'Loading gallery...';
  empty.style.display = 'none';
  grid.style.display = 'none';
  try {
    const response = await API.get('/admin/gallery');
    galleryItems = response.items || [];
    const proofs = galleryItems.filter(item => item.kind === 'proof');
    document.getElementById('statTotalProofs').textContent = proofs.length;
    document.getElementById('statAntiBanProofs').textContent = proofs.filter(item => /Anti-Ban/i.test(item.tag || '')).length;
    document.getElementById('statGameplayVideos').textContent = galleryItems.filter(item => item.kind === 'gameplay').length;
    const items = galleryItems.filter(item => item.kind === currentKind());
    grid.replaceChildren();
    for (const item of items) {
      const card = document.createElement('div');
      card.className = 'proof-admin-card';
      const mediaBox = document.createElement('div');
      mediaBox.className = 'proof-img-wrap';
      const preview = document.createElement(item.thumbnail_url || !/\.(mp4|webm|ogg|mov)(?:[?#]|$)/i.test(item.media_url) ? 'img' : 'video');
      preview.src = item.thumbnail_url || item.media_url;
      preview.style.cssText = 'width:100%;height:100%;object-fit:cover;';
      if (preview.tagName === 'VIDEO') { preview.muted = true; preview.preload = 'metadata'; }
      else preview.alt = item.title;
      mediaBox.append(preview);
      const body = document.createElement('div');
      body.className = 'proof-card-body';
      const title = document.createElement('div');
      title.className = 'proof-card-title';
      title.textContent = item.title;
      const detail = document.createElement('div');
      detail.className = 'proof-card-meta';
      detail.textContent = `${item.kind === 'gameplay' ? 'Gameplay' : 'Proof'} · ${item.platform || 'Android'}`;
      const actions = document.createElement('div');
      actions.style.cssText = 'display:flex;gap:6px;margin-top:8px;';
      const view = document.createElement('a');
      view.className = 'btn btn-secondary btn-xs';
      view.href = item.media_url;
      view.target = '_blank';
      view.rel = 'noopener';
      view.textContent = 'View';
      const edit = document.createElement('button');
      edit.className = 'btn btn-secondary btn-xs';
      edit.textContent = 'Edit';
      edit.addEventListener('click', () => openProofModal(item));
      const remove = document.createElement('button');
      remove.className = 'btn btn-danger btn-xs';
      remove.textContent = 'Delete';
      remove.addEventListener('click', () => deleteProof(item.id));
      actions.append(view, edit, remove);
      body.append(title, detail, actions);
      card.append(mediaBox, body);
      grid.append(card);
    }
    loading.style.display = 'none';
    if (items.length) grid.style.display = 'grid';
    else empty.style.display = 'block';
  } catch (err) {
    loading.textContent = 'Could not load gallery: ' + err.message;
  }
}

async function handleSaveProof(event) {
  event.preventDefault();
  const button = document.getElementById('saveProofBtn');
  const id = document.getElementById('proofEditId').value;
  const item = {
    kind: document.getElementById('proofKind').value,
    title: document.getElementById('proofTitle').value.trim(),
    media_url: document.getElementById('proofImageUrl').value.trim(),
    thumbnail_url: document.getElementById('proofThumbnailUrl').value.trim(),
    platform: document.getElementById('proofPlatform').value,
    tag: document.getElementById('proofTag').value,
    description: document.getElementById('proofDescription').value.trim()
  };
  button.disabled = true;
  try {
    await galleryWrite('/admin/gallery' + (id ? '/' + encodeURIComponent(id) : ''), id ? 'PUT' : 'POST', item);
    closeProofModal();
    document.getElementById('galleryFilter').value = item.kind;
    await loadProofs();
  } catch (err) {
    alert('Save failed: ' + err.message);
  } finally {
    button.disabled = false;
  }
}

async function deleteProof(id) {
  if (!confirm('Delete this media from the live website?')) return;
  try {
    await galleryWrite('/admin/gallery/' + encodeURIComponent(id), 'DELETE');
    await loadProofs();
  } catch (err) {
    alert('Delete failed: ' + err.message);
  }
}
