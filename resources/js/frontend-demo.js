const read = key => JSON.parse(localStorage.getItem(`sis-demo-${key}`) || '[]');
const write = (key, data) => localStorage.setItem(`sis-demo-${key}`, JSON.stringify(data));
const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, char => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[char]));
const currentStudentInvoices = () => read('invoices').filter(item => item.student.toLowerCase().includes('muhammad rizki'));
if (!localStorage.getItem('sis-demo-invoices')) write('invoices', [
    { student: 'Muhammad Rizki — Kelas 5B', type: 'Infaq', amount: 150000, issuedAt: '2026-11-01', dueAt: '2026-11-30', status: 'Belum Dibayar', note: 'Infaq bulanan' },
    { student: 'Muhammad Rizki — Kelas 5B', type: 'Uang Buku', amount: 85000, issuedAt: '2026-11-01', dueAt: '2026-12-10', status: 'Menunggu Verifikasi', note: 'Buku semester' },
    { student: 'Muhammad Rizki — Kelas 5B', type: 'Wisata', amount: 200000, issuedAt: '2026-11-01', dueAt: '2026-12-20', status: 'Lunas', note: 'Kegiatan akhir semester' },
    { student: 'Muhammad Rizki — Kelas 5B', type: 'Seragam', amount: 175000, issuedAt: '2026-11-01', dueAt: '2026-11-15', status: 'Jatuh Tempo', note: 'Seragam olahraga' },
    { student: 'Muhammad Rizki — Kelas 5B', type: 'Tagihan Lainnya', amount: 50000, issuedAt: '2026-11-01', dueAt: '2026-12-30', status: 'Belum Dibayar', note: 'Iuran kegiatan' },
]);
if (!localStorage.getItem('sis-demo-students')) write('students', [
    { name: 'Ahmad Fauzi', nisn: '0012345678', grade: '1', rombel: 'A', gender: 'Laki-laki', status: 'Aktif' },
    { name: 'Siti Aminah', nisn: '0012345679', grade: '2', rombel: 'B', gender: 'Perempuan', status: 'Aktif' },
    { name: 'Muhammad Rizki', nisn: '0012345680', grade: '5', rombel: 'B', gender: 'Laki-laki', status: 'Aktif' },
]);

document.querySelectorAll('select[name="kelas"]').forEach(select => {
    const rombel = document.querySelector('select[name="rombel"]');
    if (!rombel) return;
    const update = () => { rombel.disabled = !select.value; rombel.options[0].textContent = select.value ? 'Pilih Rombel' : 'Pilih kelas terlebih dahulu'; };
    select.addEventListener('change', update); update();
});

document.querySelectorAll('[data-demo-form="invoice"]').forEach(form => {
    const type = form.elements.type;
    const custom = form.querySelector('[data-other-name]');
    const refreshType = () => { custom.classList.toggle('is-hidden', type.value !== 'Lainnya'); custom.querySelector('input').required = type.value === 'Lainnya'; };
    type.addEventListener('change', refreshType); refreshType();
    form.addEventListener('submit', event => {
        event.preventDefault(); const data = Object.fromEntries(new FormData(form));
        data.type = data.type === 'Lainnya' ? data.customType : data.type; data.status = 'Belum Dibayar';
        const items = read('invoices'); items.push(data); write('invoices', items); form.reset(); refreshType(); renderInvoices(); alert('Tagihan disimpan pada data demonstrasi browser.');
    });
});

document.querySelectorAll('[data-demo-form="announcement"]').forEach(form => {
    const items = read('announcements'); const id = form.dataset.announcementId;
    if (id && items[id]) { form.elements.title.value = items[id].title; form.elements.publishedAt.value = items[id].publishedAt; form.elements.body.value = items[id].body; form.querySelector('[data-file-name]').textContent = items[id].attachment || 'Pilih foto atau dokumen'; }
    form.querySelector('[data-delete-announcement]')?.addEventListener('click', () => {
        if (!items[id] || !confirm('Yakin ingin menghapus pengumuman ini?')) return;
        items.splice(Number(id), 1); write('announcements', items); alert('Pengumuman berhasil dihapus.'); location.href = '/keuangan/pengumuman';
    });
    form.addEventListener('submit', event => {
        event.preventDefault(); const data = Object.fromEntries(new FormData(form)); data.attachment = form.elements.attachment.files[0]?.name || (id && items[id]?.attachment) || ''; 
        if (id && items[id]) items[id] = data; else items.push(data);
        write('announcements', items); alert('Pengumuman tersimpan pada data demonstrasi browser.'); location.href = '/keuangan/pengumuman';
    });
});
document.querySelectorAll('[data-student-placement]').forEach(form => {
    const kelas = form.elements.kelas, rombel = form.elements.rombel;
    const update = () => { rombel.disabled = !kelas.value; rombel.options[0].textContent = kelas.value ? 'Pilih Rombel' : 'Pilih kelas terlebih dahulu'; };
    const saved = JSON.parse(localStorage.getItem('sis-demo-student-placements') || '{}')[form.dataset.studentPlacement];
    const current = saved || read('students')[Number(form.dataset.studentPlacement)];
    if (current) { kelas.value = current.kelas || current.grade || ''; update(); rombel.value = current.rombel || ''; }
    kelas.addEventListener('change', update); update();
    form.addEventListener('submit', event => { event.preventDefault(); const data = Object.fromEntries(new FormData(form)); const placements = JSON.parse(localStorage.getItem('sis-demo-student-placements') || '{}'); placements[form.dataset.studentPlacement] = data; localStorage.setItem('sis-demo-student-placements', JSON.stringify(placements)); alert(`Penempatan siswa tersimpan: Kelas ${data.kelas}${data.rombel}.`); });
});
document.querySelectorAll('[data-student-create]').forEach(form => form.addEventListener('submit', event => {
    event.preventDefault(); const name = form.querySelector('.operator-student-biodata input[type="text"]')?.value || 'Siswa Baru';
    const grade = form.elements.kelas.value, rombel = form.elements.rombel.value; const students = read('students');
    students.push({ name, nisn: form.querySelectorAll('.operator-student-academic input[type="text"]')[1]?.value || '-', grade, rombel, gender: '—', status: 'Aktif' });
    write('students', students); alert('Data siswa tersimpan pada data demonstrasi browser.'); location.href = '/operator/data-siswa';
}));
document.querySelectorAll('[data-demo-form="payment"]').forEach(form => form.addEventListener('submit', event => {
    event.preventDefault(); const data = Object.fromEntries(new FormData(form)); const items = read('payments'); items.push(data); write('payments', items);
    const invoices = read('invoices'); if (invoices[data.invoice] && data.status === 'Lunas') invoices[data.invoice].status = 'Lunas'; write('invoices', invoices);
    renderKeuanganRows(); renderStudentInvoices(); alert('Pembayaran tersimpan pada data demonstrasi browser.'); form.reset();
}));
document.querySelectorAll('input[type="file"]').forEach(input => input.addEventListener('change', () => { const target = input.closest('label')?.querySelector('[data-file-name]'); if (target) target.textContent = input.files[0]?.name || 'Pilih foto atau dokumen'; }));

function renderInvoices() {
    const list = document.querySelector('[data-invoice-list]'); if (!list) return;
    const invoices = read('invoices');
    list.innerHTML = invoices.length ? `<h2>Tagihan tersimpan di perangkat ini</h2>${invoices.map(item => `<article><strong>${escapeHtml(item.student)}</strong><span>${escapeHtml(item.type)}</span><span>Rp ${Number(item.amount).toLocaleString('id-ID')}</span><span>Jatuh tempo ${escapeHtml(item.dueAt)}</span><span>${escapeHtml(item.status)}</span></article>`).join('')}` : '';
}
function renderAnnouncements() {
    const list = document.querySelector('[data-announcement-list]'); if (!list) return;
    const items = read('announcements'); if (!items.length) return;
    list.innerHTML = items.map((item, index) => `<article><h2>${escapeHtml(item.title)}</h2><p>${escapeHtml(item.body)}</p><small>${escapeHtml(item.publishedAt)} · ${escapeHtml(item.attachment || 'Tanpa lampiran')}</small><a class="keuangan-button" href="/keuangan/pengumuman/${index}/edit">Edit</a></article>`).join('');
}
function renderStudentInvoices() {
    const body = document.querySelector('[data-student-invoices]'); if (!body) return;
    let items = currentStudentInvoices();
    const selectors = document.querySelectorAll('.spp-filter-card select');
    const category = selectors[1]?.value; const status = selectors[2]?.value;
    if (category && category !== 'all') items = items.filter(item => category === 'Lainnya' ? !['Infaq', 'Uang Buku', 'Wisata', 'Seragam'].includes(item.type) : item.type === category);
    if (status && status !== 'all') items = items.filter(item => item.status === ({ lunas: 'Lunas', belum: 'Belum Dibayar', pending: 'Menunggu Verifikasi', overdue: 'Jatuh Tempo' }[status] || status));
    body.innerHTML = items.length ? items.map(item => `<tr><td>${escapeHtml(item.type)}</td><td>Rp ${Number(item.amount).toLocaleString('id-ID')}</td><td>${escapeHtml(item.dueAt)}</td><td>${escapeHtml(item.status)}</td><td><button type="button" class="keuangan-button" data-invoice-detail="${escapeHtml(item.note || item.type)}">Lihat Detail</button></td></tr>`).join('') : `<tr><td colspan="5" class="spp-table-empty-container">Belum ada tagihan pada data demonstrasi.</td></tr>`;
    body.querySelectorAll('[data-invoice-detail]').forEach(button => button.addEventListener('click', () => alert(button.dataset.invoiceDetail)));
}
function populatePaymentInvoices() {
    const select = document.querySelector('[data-demo-form="payment"] select[name="invoice"]'); if (!select) return;
    read('invoices').forEach((item, index) => { const option = document.createElement('option'); option.value = index; option.textContent = `${item.student} — ${item.type} — Rp ${Number(item.amount).toLocaleString('id-ID')}`; select.append(option); });
}
function renderKeuanganRows() {
    const body = document.querySelector('[data-keuangan-transactions]'); if (!body) return;
    let items = read('invoices');
    const filter = document.querySelector('[data-keuangan-filters]');
    if (filter) {
        const search = filter.querySelector('input[type="search"]').value.trim().toLowerCase();
        const [category, kelas, status] = [...filter.querySelectorAll('select')].map(select => select.value);
        if (search) items = items.filter(item => item.student.toLowerCase().includes(search) || item.type.toLowerCase().includes(search));
        if (category) items = items.filter(item => category === 'Lainnya' ? !['Infaq', 'Uang Buku', 'Wisata', 'Seragam'].includes(item.type) : item.type === category);
        if (kelas) items = items.filter(item => item.student.toLowerCase().includes(kelas.toLowerCase()));
        if (status) items = items.filter(item => item.status === status);
    }
    body.innerHTML = items.map((item, i) => `<tr><td>${i + 1}</td><td>${escapeHtml(item.student.split(' — ')[0])}</td><td>—</td><td>${escapeHtml(item.student.split(' — ')[1] || '—')}</td><td>${escapeHtml(item.type)}</td><td>Rp ${Number(item.amount).toLocaleString('id-ID')}</td><td>${escapeHtml(item.dueAt)}</td><td>${escapeHtml(item.status)}</td></tr>`).join('');
}
function renderStudentAnnouncements() {
    const target = document.querySelector('[data-finance-announcements]'); if (!target) return;
    target.innerHTML = read('announcements').map(item => `<article><h4>${escapeHtml(item.title)}</h4><p>${escapeHtml(item.body)}</p><small>${escapeHtml(item.publishedAt)} · ${escapeHtml(item.attachment || 'Tanpa lampiran')}</small></article>`).join('');
}
function renderOperatorStudents() {
    const body = document.querySelector('[data-operator-students]'); if (!body) return;
    const form = document.querySelector('.operator-student-filters'); const query = form?.querySelector('input[type="search"]').value.toLowerCase() || '';
    const grade = form?.querySelector('[data-student-filter="kelas"]').value || ''; const rombel = form?.querySelector('[data-student-filter="rombel"]').value || '';
    const students = read('students').map((student, id) => ({ ...student, id })).filter(student => (!query || `${student.name} ${student.nisn}`.toLowerCase().includes(query)) && (!grade || student.grade === grade) && (!rombel || student.rombel === rombel));
    body.innerHTML = students.map((student, index) => `<tr><td>${index + 1}</td><td>${escapeHtml(student.name)}</td><td>${escapeHtml(student.nisn)}</td><td>Kelas ${escapeHtml(student.grade)}</td><td>${escapeHtml(student.rombel)}</td><td>${escapeHtml(student.gender)}</td><td>${escapeHtml(student.status)}</td><td><a href="/operator/data-siswa/${student.id}/edit">Edit</a></td></tr>`).join('') || '<tr><td colspan="8">Tidak ada siswa untuk filter yang dipilih.</td></tr>';
    const total = read('students');
    const visibleCount = document.querySelector('[data-visible-students]'); const totalCount = document.querySelector('[data-total-students]');
    if (visibleCount) visibleCount.textContent = students.length;
    if (totalCount) totalCount.textContent = total.length;
    document.querySelectorAll('.operator-student-stats article strong').forEach((stat, index) => {
        const count = index === 0 ? total.length : total.filter(student => index === 1 ? student.gender === 'Laki-laki' : student.gender === 'Perempuan').length;
        stat.textContent = `${count} ${index === 2 ? 'Siswi' : 'Siswa'}`;
    });
}
function renderTeacherAttendance() {
    const body = document.querySelector('[data-teacher-attendance]'); if (!body) return;
    const rows = [
        { name: 'Ahmad Fauzi, S.Pd.I', present: 20, leave: 1, sick: 0, absent: 0 },
        { name: 'Siti Rahmawati, S.Pd.', present: 19, leave: 0, sick: 1, absent: 1 },
        { name: 'Dedi Kurniawan, S.Pd.I', present: 18, leave: 1, sick: 1, absent: 1 },
    ];
    const search = document.querySelector('[data-attendance-filters] input').value.toLowerCase();
    body.innerHTML = rows.filter(row => row.name.toLowerCase().includes(search)).map(row => `<tr><td>${escapeHtml(row.name)}</td><td>${row.present}</td><td>${row.leave}</td><td>${row.sick}</td><td>${row.absent}</td><td><button type="button" class="keuangan-button" data-attendance-detail="${escapeHtml(row.name)}">Detail</button></td></tr>`).join('');
    body.querySelectorAll('[data-attendance-detail]').forEach(button => button.addEventListener('click', () => alert(`Rekap absensi ${button.dataset.attendanceDetail}: hadir, izin, sakit, dan tanpa keterangan.`)));
}
renderInvoices(); renderAnnouncements(); renderStudentInvoices(); populatePaymentInvoices(); renderOperatorStudents();
renderKeuanganRows(); renderStudentAnnouncements(); renderTeacherAttendance();
document.querySelectorAll('[data-dashboard-bills]').forEach(el => { const pending = currentStudentInvoices().filter(item => ['Belum Dibayar', 'Jatuh Tempo'].includes(item.status)); el.innerHTML = pending.length ? `🔔 <strong>${pending.length} tagihan belum dibayar</strong>: ${pending.map(item => `${escapeHtml(item.type)} Rp ${Number(item.amount).toLocaleString('id-ID')} (jatuh tempo ${escapeHtml(item.dueAt)})`).join('; ')}. <a href="/siswa/tagihan-spp">Lihat detail</a>` : 'Tidak ada tagihan yang belum dibayar.'; });
document.querySelectorAll('[data-bill-count]').forEach(el => { const count = currentStudentInvoices().filter(item => ['Belum Dibayar', 'Jatuh Tempo'].includes(item.status)).length; el.textContent = count; el.hidden = !count; });
document.querySelectorAll('[data-keuangan-filters]').forEach(form => { form.addEventListener('input', renderKeuanganRows); form.addEventListener('change', renderKeuanganRows); form.addEventListener('reset', () => setTimeout(renderKeuanganRows)); });
document.querySelectorAll('[data-attendance-filters]').forEach(form => { form.addEventListener('input', renderTeacherAttendance); form.addEventListener('change', renderTeacherAttendance); });
document.querySelectorAll('.operator-student-filters').forEach(form => { form.addEventListener('input', renderOperatorStudents); form.addEventListener('change', renderOperatorStudents); form.addEventListener('reset', () => setTimeout(renderOperatorStudents)); });
document.querySelectorAll('.spp-filter-card select').forEach(select => select.addEventListener('change', renderStudentInvoices));
document.querySelectorAll('[data-apply-student-filters]').forEach(button => button.addEventListener('click', renderStudentInvoices));
