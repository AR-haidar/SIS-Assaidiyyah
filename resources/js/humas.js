const keyFor = section => `humas-demo-${section}`;
const activityKey = 'humas-demo-activity';
const defaultActivities = [
    { section: 'Beranda', slug: 'beranda', title: 'Banner SPMB 2025/2026 Gelombang I', description: 'Teks pengumuman & tanggal seleksi', status: 'Published', updatedAt: '2025-03-21T08:30:00+07:00', displayTime: 'Hari ini, 08:30 WIB' },
    { section: 'Prestasi Siswa', slug: 'prestasi', title: "Juara 1 Tahfidz Qur'an 5 Juz Kab. Cianjur", description: 'Santriwati M. Haikal - Kelas VI', status: 'Published', updatedAt: '2025-03-20T14:15:00+07:00', displayTime: 'Kemarin, 14:15 WIB' },
    { section: 'Profil Guru', slug: 'guru', title: 'Pembaruan Profil Guru Kelas III & IV', description: 'Penyesuaian riwayat penugasan ajar', status: 'Draft', updatedAt: '2025-03-20T11:20:00+07:00', displayTime: '20 Mar 2025, 11:20 WIB' },
    { section: 'Program & Ekstra', slug: 'program', title: 'Jadwal Latihan Pramuka & Pencak Silat', description: 'Dokumentasi latihan lapangan baru', status: 'Published', updatedAt: '2025-03-19T09:40:00+07:00', displayTime: '19 Mar 2025, 09:40 WIB' },
    { section: 'Kontak & Lokasi', slug: 'kontak', title: 'Hotline WhatsApp Humas Khusus SPMB', description: 'Penyelarasan nomor narahubung wali murid', status: 'Draft', updatedAt: '2025-03-18T16:05:00+07:00', displayTime: '18 Mar 2025, 16:05 WIB' }
];
const getActivities = () => {
    const stored = localStorage.getItem(activityKey);
    return stored === null ? defaultActivities : JSON.parse(stored);
};
const safeText = value => String(value ?? '').replace(/[&<>"']/g, character => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[character]));
let activityPage = 1;
let activityStatusFilter = '';

function renderHumasDashboard() {
    const body = document.querySelector('[data-humas-activities]');
    if (!body) return;

    const query = (document.querySelector('[data-humas-activity-search]')?.value || '').trim().toLowerCase();
    const all = getActivities();
    const filtered = all.filter(item => {
        const matchesQuery = `${item.section} ${item.title} ${item.description || ''} ${item.status}`.toLowerCase().includes(query);
        return matchesQuery && (!activityStatusFilter || item.status === activityStatusFilter);
    });
    const pageSize = 5;
    const pageCount = Math.max(1, Math.ceil(filtered.length / pageSize));
    activityPage = Math.min(activityPage, pageCount);
    const start = (activityPage - 1) * pageSize;
    const pageItems = filtered.slice(start, start + pageSize);

    body.innerHTML = pageItems.length ? pageItems.map((item, index) => {
        const status = item.status || 'Draft';
        const date = new Date(item.updatedAt);
        const formattedDate = Number.isNaN(date.valueOf()) ? '—' : new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }).format(date);
        const when = item.displayTime || formattedDate;
        const slug = safeText(item.slug || item.section.toLowerCase().replace(/[^a-z0-9]+/g, '-'));
        const title = safeText(item.title);
        const description = item.description ? `<small>${safeText(item.description)}</small>` : '';
        return `<tr>
            <td>${start + index + 1}</td>
            <td><span class="humas-activity-section ${slug}"><i aria-hidden="true">●</i>${safeText(item.section)}</span></td>
            <td><strong>${title}</strong>${description}</td>
            <td><span class="humas-status ${status.toLowerCase()}">${safeText(status)}</span></td>
            <td>${safeText(when)}</td>
            <td class="humas-activity-actions"><a href="/humas/${slug}" title="Edit konten" aria-label="Edit ${title}">✎</a><a href="/humas/${slug}" title="Lihat konten" aria-label="Lihat ${title}">◎</a></td>
        </tr>`;
    }).join('') : '<tr><td colspan="6" class="humas-empty">Tidak ada aktivitas yang cocok.</td></tr>';

    const count = document.querySelector('[data-humas-activity-count]');
    if (count) {
        const isDefaultPreview = !query && !activityStatusFilter && all.length === defaultActivities.length && all[0]?.title === defaultActivities[0].title;
        count.textContent = isDefaultPreview
            ? 'Menampilkan 5 dari 42 riwayat konten'
            : `Menampilkan ${pageItems.length ? start + 1 : 0}-${start + pageItems.length} dari ${filtered.length} riwayat konten`;
    }
    const pageButton = document.querySelector('[data-humas-page]');
    if (pageButton) {
        pageButton.textContent = String(activityPage);
        pageButton.setAttribute('aria-label', `Halaman ${activityPage} dari ${pageCount}`);
    }
    const previous = document.querySelector('[data-humas-prev]');
    const next = document.querySelector('[data-humas-next]');
    if (previous) previous.disabled = activityPage <= 1;
    if (next) next.disabled = activityPage >= pageCount;
    const filterButton = document.querySelector('[data-humas-activity-filter]');
    if (filterButton) {
        filterButton.classList.toggle('active', Boolean(activityStatusFilter));
        filterButton.title = activityStatusFilter ? `Filter: ${activityStatusFilter} (klik untuk mengubah)` : 'Filter status';
        filterButton.setAttribute('aria-pressed', String(Boolean(activityStatusFilter)));
    }
}
const programPage = document.querySelector('[data-humas-programs]');
if (programPage) {
    const samplePrograms = [
        {name:"Tahfidzul Qur'an Juz 30 & 29",category:'Program Keagamaan',kind:'unggulan',description:'Bimbingan hafalan intensif bersama pendamping berpengalaman.',status:'Published',icon:'▧'},
        {name:'Pramuka Siaga & Penggalang',category:'Ekstrakurikuler Wajib',kind:'ekstrakurikuler',description:'Pendidikan kepanduan penanaman disiplin, mandiri, dan cinta alam.',status:'Published',icon:'⚑'},
        {name:'Klub Sains & Robotik Madrasah',category:'Ekstrakurikuler Pilihan',kind:'ekstrakurikuler',description:'Eksplorasi eksperimen fisika sederhana, coding blok dasar, dan robotik.',status:'Published',icon:'♧'},
        {name:'Pencak Silat Pagar Nusa',category:'Ekstrakurikuler Pilihan',kind:'ekstrakurikuler',description:'Seni bela diri tradisional warisan ulama nusantara untuk santri.',status:'Published',icon:'⚝'},
        {name:'Hadroh & Seni Musik Islami',category:'Ekstrakurikuler Pilihan',kind:'ekstrakurikuler',description:'Latihan ketukan rebana, vokal shalawat nabi, dan kekompakan.',status:'Draft',icon:'♫'},
        {name:'Pembiasaan Ibadah Harian',category:'Program Keagamaan',kind:'unggulan',description:'Pembinaan ibadah dan akhlak mulia dalam keseharian siswa.',status:'Published',icon:'☼'},
        {name:'Kelas Bilingual',category:'Program Unggulan',kind:'unggulan',description:'Pembiasaan bahasa Arab dan Inggris dalam kegiatan belajar.',status:'Published',icon:'文'},
        {name:'Literasi dan Tahsin',category:'Program Keagamaan',kind:'unggulan',description:'Penguatan kemampuan membaca Al-Quran dan literasi dasar.',status:'Published',icon:'▤'},
        {name:'Dokter Kecil',category:'Ekstrakurikuler Pilihan',kind:'ekstrakurikuler',description:'Mengenal pola hidup sehat dan pertolongan pertama sederhana.',status:'Published',icon:'＋'},
        {name:'Futsal Madrasah',category:'Ekstrakurikuler Pilihan',kind:'ekstrakurikuler',description:'Latihan olahraga untuk membangun kebugaran dan kerja sama.',status:'Published',icon:'⚽'},
        {name:'Pembinaan Olimpiade',category:'Program Unggulan',kind:'unggulan',description:'Pendampingan persiapan kompetisi akademik tingkat daerah.',status:'Draft',icon:'☆'},
        {name:'Jumat Bersih',category:'Program Unggulan',kind:'unggulan',description:'Kegiatan rutin menjaga kebersihan dan kepedulian lingkungan.',status:'Published',icon:'♻'}
    ];
    const storageKey = 'humas-program-catalog';
    let programs = JSON.parse(localStorage.getItem(storageKey) || 'null') || samplePrograms;
    let activeCategory = 'all', page = 1, sortAscending = null;
    const pageSize = 5;
    const rows = programPage.querySelector('[data-program-rows]');
    const search = programPage.querySelector('[data-program-search]');
    const statusFilter = programPage.querySelector('[data-program-status]');
    const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[char]));
    const getFiltered = () => {
        const query = search.value.trim().toLowerCase();
        const filtered = programs.filter(item => (activeCategory === 'all' || item.kind === activeCategory) && (statusFilter.value === 'all' || item.status === statusFilter.value) && (!query || `${item.name} ${item.category} ${item.description}`.toLowerCase().includes(query)));
        if (sortAscending !== null) filtered.sort((a,b) => sortAscending ? a.name.localeCompare(b.name) : b.name.localeCompare(a.name));
        return filtered;
    };
    const renderPrograms = () => {
        const filtered = getFiltered();
        const pages = Math.max(1, Math.ceil(filtered.length / pageSize));
        page = Math.min(page, pages);
        const visible = filtered.slice((page - 1) * pageSize, page * pageSize);
        rows.innerHTML = visible.length ? visible.map((item,index) => `<tr><td>${String((page - 1) * pageSize + index + 1).padStart(2,'0')}</td><td>${item.image ? `<img class="program-thumb" src="${item.image}" alt="">` : `<span class="program-thumb">${escapeHtml(item.icon || '☆')}</span>`}</td><td>${escapeHtml(item.name)}</td><td><span class="program-category ${item.category.includes('Pilihan') ? 'optional' : ''}">${escapeHtml(item.category)}</span></td><td>${escapeHtml(item.description)}</td><td><span class="program-status ${item.status === 'Draft' ? 'draft' : ''}">${escapeHtml(item.status)}</span></td></tr>`).join('') : '<tr><td colspan="6">Belum ada program yang sesuai filter.</td></tr>';
        programPage.querySelector('[data-program-count]').textContent = filtered.length ? `Menampilkan ${((page - 1) * pageSize) + 1} - ${Math.min(page * pageSize, filtered.length)} dari ${filtered.length} data program` : 'Tidak ada program yang sesuai filter';
        programPage.querySelector('[data-program-total]').textContent = programs.length;
        programPage.querySelector('[data-program-drafts]').textContent = String(programs.filter(item => item.status === 'Draft').length).padStart(2,'0');
        programPage.querySelector('[data-program-all-count]').textContent = programs.length;
        const pagination = programPage.querySelector('[data-program-pagination]');
        pagination.innerHTML = `<button type="button" data-program-page="${page - 1}" aria-label="Halaman sebelumnya" ${page <= 1 ? 'disabled' : ''}>‹</button>${Array.from({length:pages},(_,i)=>`<button type="button" class="${i + 1 === page ? 'active' : ''}" data-program-page="${i + 1}" ${i + 1 === page ? 'aria-current="page"' : ''}>${i + 1}</button>`).join('')}<button type="button" data-program-page="${page + 1}" aria-label="Halaman berikutnya" ${page >= pages ? 'disabled' : ''}>›</button>`;
        pagination.querySelectorAll('[data-program-page]').forEach(button => button.addEventListener('click', () => { page = Number(button.dataset.programPage); renderPrograms(); }));
    };
    programPage.querySelectorAll('[data-program-category]').forEach(button => button.addEventListener('click', () => { activeCategory = button.dataset.programCategory; page = 1; programPage.querySelectorAll('[data-program-category]').forEach(tab => { const selected = tab === button; tab.classList.toggle('active', selected); tab.setAttribute('aria-pressed', String(selected)); }); renderPrograms(); }));
    search.addEventListener('input', () => { page = 1; renderPrograms(); });
    statusFilter.addEventListener('change', () => { page = 1; renderPrograms(); });
    programPage.querySelector('[data-program-sort]').addEventListener('click', () => { sortAscending = sortAscending === null ? true : !sortAscending; renderPrograms(); });
    const description = programPage.querySelector('[data-program-description]');
    description.addEventListener('input', () => { programPage.querySelector('[data-program-description-count]').textContent = description.value.length; });
    const imageInput = programPage.querySelector('[data-program-image]');
    const preview = programPage.querySelector('[data-program-image-preview]');
    const uploadCopy = programPage.querySelector('[data-program-upload-copy]');
    let imageData = '';
    imageInput.addEventListener('change', () => {
        const file = imageInput.files[0];
        if (!file) return;
        if (!/^image\/(jpeg|png|webp)$/.test(file.type) || file.size > 2 * 1024 * 1024) { alert('Pilih JPG, PNG, atau WebP maksimal 2 MB.'); imageInput.value = ''; return; }
        const reader = new FileReader(); reader.onload = () => { imageData = reader.result; preview.src = imageData; preview.hidden = false; uploadCopy.hidden = true; }; reader.readAsDataURL(file);
    });
    const form = programPage.querySelector('[data-program-form]');
    const saveProgram = mode => {
        if (!form.reportValidity()) return;
        const data = new FormData(form);
        programs.unshift({name:data.get('name').trim(),category:data.get('category'),kind:data.get('category').startsWith('Program') ? 'unggulan' : 'ekstrakurikuler',description:data.get('description').trim(),status:mode,image:imageData,icon:'☆'});
        localStorage.setItem(storageKey, JSON.stringify(programs));
        const activity = getActivities(); activity.unshift({section:'Program & Ekstrakurikuler',slug:'program',title:data.get('name').trim(),status:mode,updatedAt:new Date().toISOString()}); localStorage.setItem(activityKey, JSON.stringify(activity));
        form.reset(); imageData = ''; preview.hidden = true; uploadCopy.hidden = false; description.dispatchEvent(new Event('input')); page = 1; renderPrograms();
        alert(`Program berhasil disimpan sebagai ${mode} di browser ini.`);
    };
    form.querySelector('[data-program-save="Draft"]').addEventListener('click', () => saveProgram('Draft'));
    form.addEventListener('submit', event => { event.preventDefault(); saveProgram('Published'); });
    renderPrograms();
}
renderHumasDashboard();
document.querySelector('[data-humas-activity-search]')?.addEventListener('input', () => { activityPage = 1; renderHumasDashboard(); });
document.querySelector('[data-humas-activity-filter]')?.addEventListener('click', () => { activityStatusFilter = activityStatusFilter === '' ? 'Published' : activityStatusFilter === 'Published' ? 'Draft' : ''; activityPage = 1; renderHumasDashboard(); });
document.querySelector('[data-humas-prev]')?.addEventListener('click', () => { activityPage--; renderHumasDashboard(); });
document.querySelector('[data-humas-next]')?.addEventListener('click', () => { activityPage++; renderHumasDashboard(); });
document.querySelector('[data-humas-sync]')?.addEventListener('click', () => alert('Pemeriksaan selesai. Konten tersimpan di browser; sinkronisasi ke Landing Page memerlukan backend yang belum tersedia.'));
document.querySelectorAll('[data-humas-form]').forEach(form => {
    const section = form.dataset.humasForm;
    const storageKey = keyFor(section);
    const saved = JSON.parse(localStorage.getItem(storageKey) || 'null');
    if (saved) for (const [name, value] of Object.entries(saved.fields || {})) if (form.elements[name]) form.elements[name].value = value;
    const preview = document.querySelector('[data-humas-preview-text]');
    const updatePreview = () => { if (preview) preview.textContent = [...form.querySelectorAll('input:not([type=file]), textarea, select')].map(input => input.value).filter(Boolean).join('\n') || 'Isi form untuk melihat pratinjau.'; };
    form.addEventListener('input', updatePreview);
    form.querySelector('[data-humas-image]')?.addEventListener('change', event => { const file = event.target.files[0]; const img = form.querySelector('[data-humas-preview]'); if (file && img) { img.src = URL.createObjectURL(file); img.hidden = false; } });
    form.querySelectorAll('[data-humas-save]').forEach(button => button.addEventListener('click', () => {
        const fields = Object.fromEntries(new FormData(form));
        fields.status = button.dataset.humasSave;
        const updatedAt = new Date().toISOString();
        localStorage.setItem(storageKey, JSON.stringify({ fields, updatedAt }));
        const title = Object.values(fields).find(value => typeof value === 'string' && value.trim()) || section;
        const activity = getActivities();
        activity.unshift({ section, slug: section, title, status: fields.status, updatedAt });
        localStorage.setItem(activityKey, JSON.stringify(activity));
        alert(`Konten disimpan sebagai ${fields.status} di browser ini. Landing Page publik belum terhubung.`);
    }));
    updatePreview();
});

const homeForm = document.querySelector('[data-humas-form="beranda"]');
if (homeForm) {
    const imagePreview = homeForm.querySelector('[data-home-image-preview]');
    const imageEmpty = homeForm.querySelector('[data-home-image-empty]');
    const imageCaption = homeForm.querySelector('[data-home-image-caption]');
    const fileNameLabel = homeForm.querySelector('[data-home-file-name]');
    let imageData = '';
    const stored = JSON.parse(localStorage.getItem(keyFor('beranda')) || 'null');
    if (stored) {
        Object.entries(stored.fields || {}).forEach(([name, value]) => { if (homeForm.elements[name]) homeForm.elements[name].value = value; });
        imageData = stored.image || '';
        if (imageData) { imagePreview.src = imageData; imagePreview.hidden = false; imageEmpty.hidden = true; imageCaption.textContent = stored.imageName || 'Banner beranda'; imageCaption.hidden = false; }
    }
    const updateCounts = () => homeForm.querySelectorAll('[data-count-for]').forEach(counter => { counter.textContent = homeForm.elements[counter.dataset.countFor].value.length; });
    homeForm.addEventListener('input', updateCounts);
    updateCounts();
    const loadImage = file => {
        if (!file) return;
        if (!/^image\/(jpeg|png|webp)$/.test(file.type) || file.size > 5 * 1024 * 1024) { alert('Pilih gambar JPG, PNG, atau WebP maksimal 5 MB.'); return; }
        const reader = new FileReader();
        reader.onload = () => { imageData = reader.result; imagePreview.src = imageData; imagePreview.hidden = false; imageEmpty.hidden = true; imageCaption.textContent = file.name; imageCaption.hidden = false; };
        reader.readAsDataURL(file);
    };
    const onFileSelected = event => { const file = event.target.files[0]; if (!file) return; loadImage(file); fileNameLabel.textContent = file.name; fileNameLabel.hidden = false; };
    homeForm.querySelector('[data-home-image]')?.addEventListener('change', onFileSelected);
    homeForm.querySelector('[data-home-image-drop]')?.addEventListener('change', onFileSelected);
    homeForm.querySelector('[data-home-remove]')?.addEventListener('click', () => { imageData = ''; imagePreview.removeAttribute('src'); imagePreview.hidden = true; imageEmpty.hidden = false; imageCaption.hidden = true; fileNameLabel.hidden = true; });
    homeForm.querySelectorAll('[data-home-save]').forEach(button => button.addEventListener('click', () => {
        if (!homeForm.elements.headline.value.trim()) { alert('Judul utama wajib diisi sebelum disimpan.'); homeForm.elements.headline.focus(); return; }
        const fields = Object.fromEntries([...new FormData(homeForm)].filter(([, value]) => !(value instanceof File)));
        fields.status = button.dataset.homeSave;
        const updatedAt = new Date().toISOString();
        const record = { fields, image: imageData, imageName: imageCaption.textContent, updatedAt };
        localStorage.setItem(keyFor('beranda'), JSON.stringify(record));
        const activity = getActivities();
        activity.unshift({ section: 'Beranda', slug: 'beranda', title: fields.headline, status: fields.status, updatedAt });
        localStorage.setItem(activityKey, JSON.stringify(activity));
        alert(`Konten beranda tersimpan sebagai ${fields.status} di browser ini. Perubahan belum diterbitkan ke Landing Page publik.`);
    }));
}

const profileEditor = document.querySelector('[data-profile-editor]');
if (profileEditor) {
    const missionKey = 'humas-demo-profile-missions';
    const defaultMissions = [
        'Menyelenggarakan pendidikan Islam yang integratif antara ilmu umum dan ilmu agama.',
        "Membimbing santri dalam pembiasaan ibadah harian dan tahsin/tahfidz Al-Qur'an.",
        'Mengembangkan potensi bakat, minat, dan kreativitas santri melalui ragam ekstrakurikuler.',
        'Menumbuhkan budaya disiplin, peduli lingkungan, dan sopan santun islami.',
        'Mewujudkan tata kelola madrasah yang transparan, akuntabel, dan berbasis teknologi informasi.'
    ];
    const readMissions = () => JSON.parse(localStorage.getItem(missionKey) || 'null') || defaultMissions;
    const list = profileEditor.querySelector('[data-mission-list]');
    const vision = profileEditor.querySelector('[name="field0"]');
    const visionPreview = profileEditor.querySelector('[data-vision-preview]');
    const visionCount = profileEditor.querySelector('[data-vision-count]');
    const refreshVision = () => { visionPreview.textContent = vision.value.trim() || 'Pratinjau visi akan tampil di sini.'; visionCount.textContent = vision.value.length; };
    vision.addEventListener('input', refreshVision);
    refreshVision();
    function renderMissions() {
        list.innerHTML = readMissions().map((mission, index) => `<li><span class="humas-mission-grip" aria-hidden="true">⠿</span><span class="humas-mission-index">${index + 1}</span><span class="humas-mission-text">${safeText(mission)}<small>Dibuat: Humas Madrasah　•　Tampil Aktif</small></span><span class="humas-mission-actions"><button type="button" data-mission-up="${index}" aria-label="Naikkan urutan" ${index === 0 ? 'disabled' : ''}>⌃</button><button type="button" data-mission-edit="${index}" aria-label="Edit misi" title="Edit misi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m16 4 4 4M4 20l4-.8L19 8a2.12 2.12 0 0 0-3-3L5 16l-1 4Z"/></svg></button><button type="button" data-mission-delete="${index}" aria-label="Hapus misi" title="Hapus misi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M10 11v6m4-6v6M5 7l1 14h12l1-14M9 7V4h6v3"/></svg></button><button type="button" data-mission-down="${index}" aria-label="Turunkan urutan" ${index === readMissions().length - 1 ? 'disabled' : ''}>⌄</button></span></li>`).join('');
        let draggedMissionIndex = null;
        list.querySelectorAll('li').forEach((row, index) => {
            row.draggable = true;
            row.addEventListener('dragstart', event => { draggedMissionIndex = index; event.dataTransfer.effectAllowed = 'move'; row.classList.add('dragging'); });
            row.addEventListener('dragend', () => { draggedMissionIndex = null; row.classList.remove('dragging'); });
            row.addEventListener('dragover', event => event.preventDefault());
            row.addEventListener('drop', event => {
                event.preventDefault();
                const targetIndex = index;
                if (draggedMissionIndex === null || draggedMissionIndex === targetIndex) return;
                const items = readMissions();
                const [moved] = items.splice(draggedMissionIndex, 1);
                items.splice(targetIndex, 0, moved);
                localStorage.setItem(missionKey, JSON.stringify(items));
                renderMissions();
            });
        });        list.querySelectorAll('[data-mission-edit]').forEach(button => button.addEventListener('click', () => { const items = readMissions(); const changed = prompt('Edit butir misi', items[Number(button.dataset.missionEdit)]); if (changed?.trim()) { items[Number(button.dataset.missionEdit)] = changed.trim(); localStorage.setItem(missionKey, JSON.stringify(items)); renderMissions(); } }));
        list.querySelectorAll('[data-mission-delete]').forEach(button => button.addEventListener('click', () => { if (!confirm('Hapus butir misi ini?')) return; const items = readMissions(); items.splice(Number(button.dataset.missionDelete), 1); localStorage.setItem(missionKey, JSON.stringify(items)); renderMissions(); }));
        list.querySelectorAll('[data-mission-up], [data-mission-down]').forEach(button => button.addEventListener('click', () => { const items = readMissions(); const index = Number(button.dataset.missionUp ?? button.dataset.missionDown); const target = index + (button.hasAttribute('data-mission-up') ? -1 : 1); if (target < 0 || target >= items.length) return; [items[index], items[target]] = [items[target], items[index]]; localStorage.setItem(missionKey, JSON.stringify(items)); renderMissions(); }));
    }
    renderMissions();
    profileEditor.querySelectorAll('[data-mission-add]').forEach(button => button.addEventListener('click', () => { const mission = prompt('Masukkan butir misi baru'); if (mission?.trim()) { const items = readMissions(); items.push(mission.trim()); localStorage.setItem(missionKey, JSON.stringify(items)); renderMissions(); } }));
    profileEditor.querySelectorAll('[data-profile-save], [data-profile-publish]').forEach(button => button.addEventListener('click', () => {
        if (!vision.value.trim()) { alert('Teks visi wajib diisi.'); vision.focus(); return; }
        const status = button.dataset.profileSave || 'Published';
        const updatedAt = new Date().toISOString();
        const fields = { vision: vision.value.trim(), missions: readMissions(), status };
        localStorage.setItem(keyFor('profil'), JSON.stringify({ fields, updatedAt }));
        const activity = getActivities(); activity.unshift({ section: 'Profil & Visi Misi', slug: 'profil', title: 'Pembaruan visi dan misi madrasah', status, updatedAt }); localStorage.setItem(activityKey, JSON.stringify(activity));
        alert(`Profil & Visi Misi tersimpan sebagai ${status} di browser ini. Landing Page publik belum terhubung.`);
    }));
    const saved = JSON.parse(localStorage.getItem(keyFor('profil')) || 'null');
    if (saved?.fields?.vision) { vision.value = saved.fields.vision; refreshVision(); }
    if (saved?.fields?.missions) { localStorage.setItem(missionKey, JSON.stringify(saved.fields.missions)); renderMissions(); }
}

const teacherEditor = document.querySelector('[data-humas-teachers]');
if (teacherEditor) {
    const teacherKey = keyFor('guru');
    const modal = teacherEditor.querySelector('[data-teacher-modal]');
    const form = teacherEditor.querySelector('[data-teacher-form]');
    const rows = teacherEditor.querySelector('[data-teacher-rows]');
    let currentEdit = null, page = 1;
    const getTeachers = () => JSON.parse(localStorage.getItem(teacherKey) || '[]');
    const setTeachers = teachers => localStorage.setItem(teacherKey, JSON.stringify(teachers));
    const roleFilter = teacherEditor.querySelector('[name="role"]');
    const exampleKey = `${teacherKey}-examples-loaded`;
    if (getTeachers().length === 0) {
        setTeachers([
            {name:'Ustadz H. Ahmad Fauzi, S.Pd.I',role:"Guru Al-Qur'an Hadits & Fiqih",description:"Mendidik dengan hati, menanamkan akhlakul karimah sebagai pondasi generasi Qur'ani masa depan.",status:'Published',order:1,initials:'AF'},
            {name:'Ustadzah Siti Maryam, S.Pd',role:'Wali Kelas IV-A & Tematik',description:'Menumbuhkan rasa ingin tahu dan kegembiraan belajar sains serta literasi sejak dini.',status:'Published',order:2,initials:'SM'},
            {name:'Ustadz M. Ridwan, S.Ag',role:'Guru Bahasa Arab & Nahwu',description:'Bahasa Arab adalah kunci memahami khazanah Islam, mari pelajari dengan santun dan interaktif.',status:'Published',order:3,initials:'MR'},
            {name:'Ustadzah Nurul Hidayah, S.Si',role:'Guru IPA & Riset Cilik',description:'Membimbing saintis muda madrasah yang berpikir kritis dan berakhlak mulia.',status:'Published',order:4,initials:'NH'},
            {name:'Ustadz Budi Santoso, S.Pd.Jas',role:'Guru PJOK & Pembina Futsal',description:'Kesehatan jasmani dan kedisiplinan mental adalah pilar ketangguhan anak bangsa.',status:'Draft',order:5,initials:'BS'},
            {name:'Ustadzah Rina Kurnia, S.Pd',role:'Guru Kelas II & Literasi',description:'Mendampingi anak belajar dengan penuh perhatian dan semangat.',status:'Published',order:6,initials:'RK'},
            {name:'Ustadz Dedi Mulyana, S.Pd.I',role:'Pembina Tahfidz',description:'Membiasakan cinta Al-Qur’an melalui talaqqi dan murojaah harian.',status:'Published',order:7,initials:'DM'},
            {name:'Ustadzah Lilis Sulastri, S.Pd',role:'Guru Matematika',description:'Mengajak siswa berpikir logis, tekun, dan percaya diri.',status:'Draft',order:8,initials:'LS'}
        ]);
        localStorage.setItem(exampleKey, '1');
    }
    const demoTeacherPhotos = {AF:'/images/humas/guru-demo/ahmad.jpg',SM:'/images/humas/guru-demo/siti.jpg',MR:'/images/humas/guru-demo/ridwan.jpg',NH:'/images/humas/guru-demo/nurul.jpg',BS:'/images/humas/guru-demo/budi.jpg'};
    const demoTeacherMeta = {AF:'NIP. 198204122009121003 • Pengajar Senior',SM:'NIP. 198809202014022001 • Koordinator Kelas Awal',MR:'NIP. 197906152008011012 • Pembina Bahasa Asing',NH:'NIP. 199201182019032009 • Pengampu STEM Madrasah',BS:'NIP. 19950311202011002 • Pelatih Prestasi Olahraga'};
    const currentTeachers = getTeachers();
    let updatedTeacherExamples = false;
    currentTeachers.forEach(item => { if (item.initials && demoTeacherPhotos[item.initials]) { if (!item.photo) { item.photo = demoTeacherPhotos[item.initials]; updatedTeacherExamples = true; } if (!item.meta) { item.meta = demoTeacherMeta[item.initials]; updatedTeacherExamples = true; } } });
    if (updatedTeacherExamples) setTeachers(currentTeachers);
    function renderTeachers() {
        const all = getTeachers();
        const search = teacherEditor.querySelector('[name="search"]').value.trim().toLowerCase();
        const visibility = teacherEditor.querySelector('[name="visibility"]').value;
        const role = roleFilter.value;
        roleFilter.innerHTML = '<option value="">Semua Jabatan / Tingkat</option>' + [...new Set(all.map(item => item.role).filter(Boolean))].map(value => `<option>${safeText(value)}</option>`).join('');
        roleFilter.value = role;
        const filtered = all.map((item, index) => ({ ...item, index })).filter(item => (!search || `${item.name} ${item.role} ${item.description}`.toLowerCase().includes(search)) && (!visibility || item.status === visibility) && (!role || item.role === role));
        const pageSize = 5, pageCount = Math.max(1, Math.ceil(filtered.length / pageSize));
        page = Math.min(page, pageCount);
        const current = filtered.slice((page - 1) * pageSize, page * pageSize);
        rows.innerHTML = current.length ? current.map(item => `<tr><td>${item.index + 1}</td><td><span class="humas-teacher-grip" aria-hidden="true">⠿</span><input class="humas-teacher-order" type="number" min="1" value="${item.order || item.index + 1}" data-teacher-order="${item.index}" aria-label="Urutan tampilan"></td><td>${item.photo ? `<img class="humas-teacher-photo" src="${item.photo}" alt="Foto ${safeText(item.name)}">` : '<span class="humas-teacher-photo-placeholder">—</span>'}</td><td>${safeText(item.name)}</td><td><span class="humas-teacher-badge">${safeText(item.role)}</span></td><td><em>${safeText(item.description)}</em></td><td><label><input class="humas-teacher-visibility" type="checkbox" data-teacher-visibility="${item.index}" ${item.status === 'Published' ? 'checked' : ''}>${item.status === 'Published' ? 'Tampil Aktif' : 'Disembunyikan'}</label></td><td><span class="humas-teacher-actions"><button type="button" data-teacher-edit="${item.index}" aria-label="Edit profil" title="Edit"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m16 4 4 4M4 20l4-.8L19 8a2.12 2.12 0 0 0-3-3L5 16l-1 4Z"/></svg></button><button type="button" data-teacher-delete="${item.index}" aria-label="Hapus profil" title="Hapus"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M10 11v6m4-6v6M5 7l1 14h12l1-14M9 7V4h6v3"/></svg></button></span></td></tr>`).join('') : '<tr><td colspan="8">Belum ada profil guru. Tambahkan profil untuk mulai mengelola sorotan landing page.</td></tr>';
        rows.querySelectorAll('tr').forEach((row,index) => { if (current[index]?.meta && row.cells[3]) row.cells[3].innerHTML = `${safeText(current[index].name)}<small>${safeText(current[index].meta)}</small>`; });
        teacherEditor.querySelector('[data-teacher-published]').textContent = `${all.filter(item => item.status === 'Published').length} Guru`;
        teacherEditor.querySelector('[data-teacher-draft]').textContent = `${all.filter(item => item.status !== 'Published').length} Profil`;
        teacherEditor.querySelector('[data-teacher-summary]').textContent = filtered.length ? `Menampilkan ${(page - 1) * pageSize + 1}-${(page - 1) * pageSize + current.length} dari ${filtered.length} data sorotan aktif di landing page` : 'Belum ada profil guru tersimpan.';
        const pagination = teacherEditor.querySelector('[data-teacher-pagination]');
        pagination.innerHTML = `<button type="button" data-teacher-page="${page - 1}" aria-label="Halaman sebelumnya" ${page <= 1 ? 'disabled' : ''}>‹</button>${Array.from({length:pageCount},(_,index)=>`<button type="button" class="${index + 1 === page ? 'active' : ''}" data-teacher-page="${index + 1}" ${index + 1 === page ? 'aria-current="page"' : ''}>${index + 1}</button>`).join('')}<button type="button" data-teacher-page="${page + 1}" aria-label="Halaman berikutnya" ${page >= pageCount ? 'disabled' : ''}>›</button>`;
        pagination.querySelectorAll('[data-teacher-page]').forEach(button => button.addEventListener('click', () => { page = Number(button.dataset.teacherPage); renderTeachers(); }));
        const featured = all.filter(item => item.status === 'Published').sort((a, b) => (a.order || 0) - (b.order || 0)).slice(0, 3);
        teacherEditor.querySelector('[data-teacher-preview-cards]').innerHTML = featured.map(item => `<article>${item.photo ? `<img src="${item.photo}" alt="${safeText(item.name)}">` : '<div class="humas-teacher-photo-placeholder">Foto belum ditambahkan</div>'}<h3>${safeText(item.name)}</h3><span>${safeText(item.role)}</span><p>“${safeText(item.description)}”</p></article>`).join('') || '<p class="humas-teacher-empty">Belum ada profil Published untuk pratinjau.</p>';
        rows.querySelectorAll('[data-teacher-edit]').forEach(button => button.addEventListener('click', () => openTeacher(Number(button.dataset.teacherEdit))));
        rows.querySelectorAll('[data-teacher-delete]').forEach(button => button.addEventListener('click', () => { if (!confirm('Hapus profil guru ini?')) return; const items = getTeachers(); items.splice(Number(button.dataset.teacherDelete), 1); setTeachers(items); renderTeachers(); }));
        rows.querySelectorAll('[data-teacher-visibility]').forEach(input => input.addEventListener('change', () => { const items = getTeachers(); items[Number(input.dataset.teacherVisibility)].status = input.checked ? 'Published' : 'Draft'; setTeachers(items); renderTeachers(); }));
        rows.querySelectorAll('[data-teacher-order]').forEach(input => input.addEventListener('change', () => { const items = getTeachers(); items[Number(input.dataset.teacherOrder)].order = Math.max(1, Number(input.value) || 1); setTeachers(items); renderTeachers(); }));
    }
    function openTeacher(index = null) {
        currentEdit = index;
        form.reset();
        form.querySelector('[data-teacher-form-title]').textContent = index === null ? 'Tambah Sorotan Profil Guru' : 'Edit Sorotan Profil Guru';
        const item = index === null ? null : getTeachers()[index];
        if (item) { form.elements.name.value = item.name; form.elements.role.value = item.role; form.elements.description.value = item.description; form.elements.status.value = item.status; }
        const photo = form.querySelector('[data-teacher-photo-preview]');
        photo.src = item?.photo || ''; photo.hidden = !item?.photo;
        modal.hidden = false;
    }
    teacherEditor.querySelector('[data-teacher-add]')?.addEventListener('click', () => openTeacher());
    teacherEditor.querySelector('[data-teacher-close]').addEventListener('click', () => { modal.hidden = true; });
    modal.addEventListener('click', event => { if (event.target === modal) modal.hidden = true; });
    form.elements.photo.addEventListener('change', event => { const file = event.target.files[0]; const preview = form.querySelector('[data-teacher-photo-preview]'); if (!file) return; if (file.size > 2 * 1024 * 1024) { alert('Ukuran foto maksimal 2 MB.'); event.target.value = ''; return; } preview.src = URL.createObjectURL(file); preview.hidden = false; });
    form.addEventListener('submit', event => {
        event.preventDefault();
        const items = getTeachers(), old = currentEdit === null ? {} : items[currentEdit];
        const file = form.elements.photo.files[0];
        const save = photo => {
            const item = { name: form.elements.name.value.trim(), role: form.elements.role.value.trim(), description: form.elements.description.value.trim(), status: form.elements.status.value, photo: photo || old.photo || '', order: old.order || items.length + 1 };
            if (currentEdit === null) items.push(item); else items[currentEdit] = item;
            setTeachers(items); modal.hidden = true; renderTeachers();
            const activity = getActivities(); activity.unshift({ section: 'Profil Guru', slug: 'guru', title: item.name, status: item.status, updatedAt: new Date().toISOString() }); localStorage.setItem(activityKey, JSON.stringify(activity));
        };
        if (!file) { save(old.photo); return; }
        const reader = new FileReader(); reader.onload = () => save(reader.result); reader.readAsDataURL(file);
    });
    teacherEditor.querySelectorAll('[name="search"], [name="role"], [name="visibility"]').forEach(control => { control.addEventListener('input', () => { page = 1; renderTeachers(); }); control.addEventListener('change', () => { page = 1; renderTeachers(); }); });
    teacherEditor.querySelector('[data-teacher-filter-form]').addEventListener('submit', event => { event.preventDefault(); page = 1; renderTeachers(); });
    teacherEditor.querySelector('[data-teacher-publish]')?.addEventListener('click', () => { const items = getTeachers(); items.forEach(item => item.status = 'Published'); setTeachers(items); renderTeachers(); alert('Semua profil ditandai Published di browser ini.'); });
    renderTeachers();
}

const teacherCreateForm = document.querySelector('[data-teacher-create-form]');
if (teacherCreateForm) {
    const imageInput = teacherCreateForm.querySelector('[data-teacher-create-photo]');
    const imagePreview = teacherCreateForm.querySelector('[data-teacher-create-preview]');
    const uploadCopy = teacherCreateForm.querySelector('[data-teacher-upload-copy]');
    const fileName = teacherCreateForm.querySelector('[data-teacher-file-name]');
    const description = teacherCreateForm.elements.description;
    const count = teacherCreateForm.querySelector('[data-teacher-description-count]');
    description.addEventListener('input', () => { count.textContent = description.value.length; });
    teacherCreateForm.querySelector('[data-teacher-pick]').addEventListener('click', () => imageInput.click());
    const showPhoto = file => {
        if (!file) return;
        if (!/^image\/(jpeg|png|webp)$/.test(file.type) || file.size > 2 * 1024 * 1024) { alert('Pilih foto JPG, PNG, atau WebP maksimal 2 MB.'); imageInput.value = ''; return; }
        imagePreview.src = URL.createObjectURL(file); imagePreview.hidden = false; uploadCopy.hidden = true; fileName.textContent = file.name;
    };
    imageInput.addEventListener('change', () => showPhoto(imageInput.files[0]));
    const upload = teacherCreateForm.querySelector('[data-teacher-upload]');
    upload.addEventListener('dragover', event => { event.preventDefault(); upload.classList.add('is-dragging'); });
    upload.addEventListener('dragleave', () => upload.classList.remove('is-dragging'));
    upload.addEventListener('drop', event => { event.preventDefault(); upload.classList.remove('is-dragging'); showPhoto(event.dataTransfer.files[0]); });
    teacherCreateForm.addEventListener('submit', event => {
        event.preventDefault();
        const file = imageInput.files[0];
        if (!file) { alert('Foto profil wajib dipilih.'); return; }
        const reader = new FileReader();
        reader.onload = () => {
            const teachers = JSON.parse(localStorage.getItem(keyFor('guru')) || '[]');
            const status = event.submitter?.value || teacherCreateForm.elements.status.value;
            const item = { name: teacherCreateForm.elements.name.value.trim(), role: teacherCreateForm.elements.role.value.trim(), description: description.value.trim(), status, photo: reader.result, order: teachers.length + 1 };
            teachers.push(item); localStorage.setItem(keyFor('guru'), JSON.stringify(teachers));
            const activity = getActivities(); activity.unshift({ section: 'Profil Guru', slug: 'guru', title: item.name, status, updatedAt: new Date().toISOString() }); localStorage.setItem(activityKey, JSON.stringify(activity));
            alert(`Profil ${item.name} tersimpan sebagai ${status} di browser ini.`);
            window.location.href = '/humas/guru';
        };
        reader.readAsDataURL(file);
    });
}

const achievementPage = document.querySelector('[data-humas-achievements]');
if (achievementPage) {
    const baseAchievements = [
        {title:"Juara 1 MTQ Tingkat Kabupaten Cianjur",subtitle:"Cabang Tilawah & Tahfidz 5 Juz Putra • Kab. Cianjur",category:'Keagamaan',winner:'Ahmad Fauzi',className:'Kelas VI-A',status:'Published',icon:'✿'},
        {title:'Medali Emas Olimpiade Sains Madrasah (KSM)',subtitle:'Bidang Matematika Terintegrasi • Tingkat Jawa Barat',category:'Akademik',winner:'Nadia Safitri',className:'Kelas V-E',status:'Published',icon:'♧'},
        {title:'Juara 2 Pagar Nusa Championship Se-Cianjur',subtitle:'Tanding Putra Kategori Usia Dini • Kab. Cianjur',category:'Olahraga & Seni',winner:'Rizky Maulana',className:'Kelas IV-B',status:'Published',icon:'⚝'},
        {title:'Juara Harapan 1 Festival Hadroh Santri Milenial',subtitle:'Tingkat Wilayah II Jawa Barat • Bandung Barat',category:'Olahraga & Seni',winner:'Tim Hadroh Assaidiyyah',className:'Regu Santri',status:'Published',icon:'♫'}
    ];
    const extraAchievementTitles = [
        ['Juara 1 Tahfidz Juz 30 Tingkat Kecamatan','Keagamaan'],['Juara 2 Kaligrafi Islami Kabupaten','Keagamaan'],['Juara 1 Lomba Adzan Cilik','Keagamaan'],['Juara 3 MHQ Putri Tingkat Kabupaten','Keagamaan'],['Penghargaan Siswa Teladan Madrasah','Keagamaan'],['Juara 1 Cerdas Cermat PAI','Keagamaan'],['Juara 2 Tartil Al-Qur’an','Keagamaan'],['Juara 1 Pidato Bahasa Arab','Keagamaan'],['Juara 1 Olimpiade Matematika','Akademik'],['Juara 2 Kompetisi Sains Madrasah','Akademik'],['Medali Perak Lomba IPA','Akademik'],['Juara 1 Lomba Literasi','Akademik'],['Finalis Olimpiade Bahasa Indonesia','Akademik'],['Juara 3 Cerdas Cermat Umum','Akademik']
    ];
    const demoAchievements = [...baseAchievements, ...extraAchievementTitles.map(([title,category],index) => ({title,subtitle:`Tingkat ${index % 2 ? 'Kabupaten Cianjur' : 'Provinsi Jawa Barat'} • Tahun 2025`,category,winner:['Nadia Salsabila','Fahri Ramadhan','Siti Aisyah','Rizal Maulana'][index%4],className:`Kelas ${['IV-A','V-B','VI-A','III-C'][index%4]}`,status:index === 5 || index === 11 ? 'Draft' : 'Published',icon:category === 'Akademik' ? '♧' : '✿'}))];
    const achievementKey = 'humas-achievement-catalog';
    let achievements = JSON.parse(localStorage.getItem(achievementKey) || 'null') || demoAchievements;
    let achievementCurrentPage = 1;
    const pageSize = 4;
    const searchForm = achievementPage.querySelector('[data-achievement-filter]');
    const results = achievementPage.querySelector('[data-achievement-rows]');
    const safe = value => String(value ?? '').replace(/[&<>"']/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[char]));
    const renderAchievements = () => {
        const query = searchForm.elements.search.value.trim().toLowerCase();
        const category = searchForm.elements.category.value;
        const status = searchForm.elements.status.value;
        const filtered = achievements.filter(item => (!query || `${item.title} ${item.winner} ${item.subtitle}`.toLowerCase().includes(query)) && (!category || item.category === category) && (!status || item.status === status));
        const pages = Math.max(1, Math.ceil(filtered.length / pageSize));
        achievementCurrentPage = Math.max(1, Math.min(achievementCurrentPage, pages));
        const visible = filtered.slice((achievementCurrentPage - 1) * pageSize, achievementCurrentPage * pageSize);
        results.innerHTML = visible.length ? visible.map((item,index) => `<tr><td>${(achievementCurrentPage - 1) * pageSize + index + 1}</td><td>${item.image ? `<img class="achievement-photo" src="${item.image}" alt="Foto prestasi">` : `<span class="achievement-photo">${safe(item.icon || '☆')}</span>`}</td><td>${safe(item.title)}<small>${safe(item.subtitle)}</small></td><td><span class="achievement-tag">${safe(item.category)}</span></td><td>${safe(item.winner)}<small>${safe(item.className)}</small></td></tr>`).join('') : '<tr><td colspan="5">Tidak ada prestasi yang sesuai dengan filter.</td></tr>';
        achievementPage.querySelector('[data-achievement-total]').textContent = achievements.length;
        achievementPage.querySelector('[data-achievement-summary]').textContent = filtered.length ? `Menampilkan ${((achievementCurrentPage - 1) * pageSize) + 1} - ${Math.min(achievementCurrentPage * pageSize, filtered.length)} dari ${filtered.length} data penghargaan santri` : 'Tidak ada prestasi yang sesuai filter';
        const nav = achievementPage.querySelector('[data-achievement-pagination]');
        nav.innerHTML = `<button type="button" data-achievement-page="${achievementCurrentPage - 1}" ${achievementCurrentPage <= 1 ? 'disabled' : ''}>‹</button>${Array.from({length:pages},(_,i)=>`<button type="button" class="${i + 1 === achievementCurrentPage ? 'active' : ''}" data-achievement-page="${i + 1}">${i + 1}</button>`).join('')}<button type="button" data-achievement-page="${achievementCurrentPage + 1}" ${achievementCurrentPage >= pages ? 'disabled' : ''}>›</button>`;
        nav.querySelectorAll('[data-achievement-page]').forEach(button => button.addEventListener('click', () => { achievementCurrentPage = Number(button.dataset.achievementPage); renderAchievements(); }));
    };
    searchForm.addEventListener('submit', event => { event.preventDefault(); achievementCurrentPage = 1; renderAchievements(); });
    searchForm.querySelectorAll('input,select').forEach(control => control.addEventListener('input', () => { achievementCurrentPage = 1; renderAchievements(); }));
    achievementPage.querySelector('[data-achievement-focus]').addEventListener('click', () => achievementPage.querySelector('[data-achievement-form]').scrollIntoView({behavior:'smooth',block:'start'}));
    const form = achievementPage.querySelector('[data-achievement-form]');
    const imageInput = form.querySelector('[data-achievement-image]');
    const imagePreview = form.querySelector('[data-achievement-image-preview]');
    const uploadCopy = form.querySelector('[data-achievement-upload-copy]');
    let imageData = '';
    imageInput.addEventListener('change', () => {
        const file = imageInput.files[0]; if (!file) return;
        if (!/^image\/(jpeg|png|webp)$/.test(file.type) || file.size > 3 * 1024 * 1024) { alert('Pilih foto JPG, PNG, atau WebP maksimal 3 MB.'); imageInput.value = ''; return; }
        const reader = new FileReader(); reader.onload = () => { imageData = reader.result; imagePreview.src = imageData; imagePreview.hidden = false; uploadCopy.hidden = true; }; reader.readAsDataURL(file);
    });
    form.querySelector('[data-achievement-save="Draft"]').addEventListener('click', () => saveAchievement('Draft'));
    form.addEventListener('submit', event => { event.preventDefault(); saveAchievement('Published'); });
    function saveAchievement(status) {
        if (!form.reportValidity()) return;
        const data = new FormData(form);
        const categoryMap = {Keagamaan:'Keagamaan',Akademik:'Akademik','Olahraga & Seni':'Olahraga & Seni'};
        const item = {title:data.get('title').trim(),subtitle:`${data.get('level')} • ${data.get('date').trim()}`,category:categoryMap[data.get('category')],winner:data.get('winner').trim(),className:data.get('date').trim(),status,image:imageData,icon:'☆'};
        achievements.unshift(item); localStorage.setItem(achievementKey, JSON.stringify(achievements));
        const activity = getActivities(); activity.unshift({section:'Prestasi Siswa',slug:'prestasi',title:item.title,status,updatedAt:new Date().toISOString()}); localStorage.setItem(activityKey, JSON.stringify(activity));
        form.reset(); imageData=''; imagePreview.hidden=true; uploadCopy.hidden=false; achievementCurrentPage=1; renderAchievements(); alert(`Prestasi berhasil disimpan sebagai ${status} di browser ini.`);
    }
    renderAchievements();
}

const contactPage = document.querySelector('[data-humas-contact]');
if (contactPage) {
    const contactForm = contactPage.querySelector('[data-contact-form]');
    const contactKey = 'humas-demo-contact';
    const contactDefaults = {address:"Jl. Assaidiyyah No. 12, Cipanas, Kabupaten Cianjur, Jawa Barat 43253",district:'Cipanas, Cianjur',postal:'43253',map:'maps.app.goo.gl/miassaidiyyahcipanas',phone:'(0263) 123456',whatsapp:'+62 812-3456-7890',email:'info@miassaidiyyah.sch.id',admission_email:'spmb@miassaidiyyah.sch.id',hours:'Senin – Kamis: 07.30 – 14.00 WIB | Jumat: 07.30 – 11.00 WIB',service_note:'Pelayanan penerimaan tamu, konsultasi pendidikan, dan administrasi loket madrasah.',facebook:'https://facebook.com/miassaidiyyah.cipanas',instagram:'@miassaidiyyah_cipanas',youtube:'https://youtube.com/@miassaidiyyahcipanas',tiktok:'@miassaidiyyah'};
    const readContact = () => JSON.parse(localStorage.getItem(contactKey) || 'null') || contactDefaults;
    const fillContactForm = () => Object.entries(readContact()).forEach(([name,value]) => { if (contactForm.elements[name]) contactForm.elements[name].value = value; });
    fillContactForm();
    contactForm.querySelectorAll('[data-contact-save]').forEach(button => button.addEventListener('click', () => {
        const values = Object.fromEntries(new FormData(contactForm).entries());
        localStorage.setItem(contactKey, JSON.stringify(values));
        const status = button.dataset.contactSave;
        const activity = getActivities(); activity.unshift({section:'Kontak',slug:'kontak',title:'Informasi kontak & media resmi',status,updatedAt:new Date().toISOString()}); localStorage.setItem(activityKey, JSON.stringify(activity));
        alert(`Informasi kontak tersimpan sebagai ${status} di browser ini.`);
    }));
    contactForm.querySelector('[type="reset"]').addEventListener('click', event => { event.preventDefault(); fillContactForm(); });
    contactPage.querySelector('[data-contact-preview]').addEventListener('click', () => window.open('/', '_blank', 'noopener'));
    contactPage.querySelector('[data-contact-open-map]').addEventListener('click', () => { const value = contactForm.elements.map.value.trim(); if (value) window.open(/^https?:\/\//i.test(value) ? value : `https://${value}`, '_blank', 'noopener'); });
}
