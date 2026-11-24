const read = key => JSON.parse(localStorage.getItem(`sis-demo-${key}`) || '[]');
const write = (key, data) => localStorage.setItem(`sis-demo-${key}`, JSON.stringify(data));
const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, char => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[char]));

const studentXlsxInput = document.querySelector('[data-student-xlsx]');
studentXlsxInput?.addEventListener('change', async () => {
    const file = studentXlsxInput.files?.[0];
    const message = document.querySelector('[data-student-import-message]');
    if (!file) return;
    message.textContent = 'Membaca file Excel…';
    try {
        const XLSX = await import('xlsx');
        const isCsv = file.name.toLowerCase().endsWith('.csv');
        if (!isCsv && !file.name.toLowerCase().endsWith('.xlsx')) throw new Error('Format file harus .xlsx atau .csv.');
        const workbook = isCsv
            ? XLSX.read(await file.text(), { type: 'string', raw: false, cellDates: true })
            : XLSX.read(await file.arrayBuffer(), { type: 'array', cellDates: true });
        const imported = [];
        const headers = ['No', 'Nama Lengkap', 'NISN', 'NIK', 'Tempat Lahir', 'Tanggal Lahir', 'Tingkat - Rombel', 'Umur', 'Status', 'Jenis Kelamin', 'Alamat', 'No Telepon', 'Kebutuhan Khusus', 'Disabilitas', 'Nomor KIP/PIP', 'Nama Ayah Kandung', 'Nama Ibu Kandung', 'Nama Wali'];
        const normalize = value => String(value ?? '').trim().toLowerCase();
        const valueText = value => value instanceof Date ? value.toISOString().slice(0, 10) : String(value ?? '').trim();
        for (const sheetName of workbook.SheetNames) {
            const rows = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], { header: 1, defval: '', raw: false });
            const headerRow = rows.findIndex(row => row.some(cell => normalize(cell) === 'nama lengkap') && row.some(cell => normalize(cell) === 'nisn'));
            if (headerRow < 0) continue;
            const columns = Object.fromEntries(headers.map(header => [header, rows[headerRow].findIndex(cell => normalize(cell) === normalize(header))]));
            for (const row of rows.slice(headerRow + 1)) {
                const get = header => columns[header] < 0 ? '' : valueText(row[columns[header]]);
                const name = get('Nama Lengkap');
                const nisn = get('NISN').replace(/\D/g, '').padStart(10, '0');
                if (!name || !nisn || /^0+$/.test(nisn)) continue;
                const placement = get('Tingkat - Rombel').match(/kelas\s*(\d+)\s*(?:-|\s)\s*(\w+)?/i);
                imported.push({
                    name, nisn, nik: get('NIK').replace(/^'/, ''), grade: placement?.[1] || '', rombel: placement?.[2] || '',
                    gender: get('Jenis Kelamin') || '—', status: get('Status') || 'Aktif', birthPlace: get('Tempat Lahir'),
                    birthDate: get('Tanggal Lahir'), age: get('Umur'), levelRombel: get('Tingkat - Rombel'), address: get('Alamat'), phone: get('No Telepon'), father: get('Nama Ayah Kandung'),
                    mother: get('Nama Ibu Kandung'), guardian: get('Nama Wali'), specialNeeds: get('Kebutuhan Khusus'),
                    disability: get('Disabilitas'), kip: get('Nomor KIP/PIP')
                });
            }
        }
        if (!imported.length) throw new Error('Tidak ditemukan baris siswa. Pastikan header kolom sesuai file contoh.');
        const students = read('students');
        const knownNisn = new Set(students.map(student => String(student.nisn || '').replace(/\D/g, '').padStart(10, '0')));
        let added = 0, duplicates = 0;
        for (const student of imported) {
            if (knownNisn.has(student.nisn)) { duplicates++; continue; }
            knownNisn.add(student.nisn);
            students.push(student);
            added++;
        }
        write('students', students);
        renderOperatorStudents();
        message.textContent = `Impor selesai: ${added} siswa ditambahkan, ${duplicates} duplikat dilewati dari ${imported.length} baris.`;
    } catch (error) {
        message.textContent = error.message || 'File gagal dibaca. Pastikan file berformat .xlsx.';
    } finally {
        studentXlsxInput.value = '';
    }
});

const teacherFileInput = document.querySelector('[data-teacher-file]');
teacherFileInput?.addEventListener('change', async () => {
    const file = teacherFileInput.files?.[0];
    const message = document.querySelector('[data-teacher-import-message]');
    if (!file) return;
    message.textContent = 'Membaca file…';
    try {
        const XLSX = await import('xlsx');
        const isCsv = file.name.toLowerCase().endsWith('.csv');
        if (!isCsv && !file.name.toLowerCase().endsWith('.xlsx')) throw new Error('Format file harus .xlsx atau .csv.');
        const workbook = isCsv ? XLSX.read(await file.text(), { type: 'string', raw: false }) : XLSX.read(await file.arrayBuffer(), { type: 'array', cellDates: true });
        const teachers = JSON.parse(localStorage.getItem('sis-demo-teachers') || '[]');
        const key = value => String(value ?? '').trim().toLowerCase();
        let added = 0, duplicates = 0;
        for (const sheetName of workbook.SheetNames) {
            const rows = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], { header: 1, defval: '', raw: false });
            const headerIndex = rows.findIndex(row => row.some(cell => key(cell) === 'nama lengkap') && row.some(cell => key(cell) === 'status kepegawaian'));
            if (headerIndex < 0) continue;
            const headers = rows[headerIndex].map(key);
            const get = (row, name) => String(row[headers.indexOf(key(name))] ?? '').trim().replace(/^'/, '');
            for (const row of rows.slice(headerIndex + 1)) {
                const name = get(row, 'Nama Lengkap');
                if (!name) continue;
                const nip = get(row, 'NIP'), nuptk = get(row, 'NUPTK');
                if (teachers.some(item => (nip && item.nip === nip) || (nuptk && item.nuptk === nuptk))) { duplicates++; continue; }
                teachers.push({ name, nik: get(row, 'NIK'), nuptk, status: get(row, 'Status Kepegawaian'), nip, gender: get(row, 'Jenis Kelamin'), birthPlace: get(row, 'Tempat Lahir'), birthDate: get(row, 'Tanggal Lahir'), phone: get(row, 'Nomor Handphone'), email: get(row, 'Email'), accountEmail: get(row, 'Email Akun Madrasah Digital') || get(row, 'Email Akun Madrasah Hebat'), task: get(row, 'Tugas'), subject: get(row, 'Mata Pelajaran'), placement: get(row, 'Penempatan'), jtm: get(row, 'Total JTM') });
                added++;
            }
        }
        if (!added && !duplicates) throw new Error('Header file tidak cocok dengan format Daftar GTK.');
        localStorage.setItem('sis-demo-teachers', JSON.stringify(teachers));
        renderOperatorTeachers();
        message.textContent = `Impor selesai: ${added} data ditambahkan, ${duplicates} duplikat dilewati.`;
    } catch (error) { message.textContent = error.message || 'File gagal dibaca.'; }
    finally { teacherFileInput.value = ''; }
});
if (!localStorage.getItem('sis-demo-invoices')) write('invoices', [
    { student: 'Muhammad Rizki — Kelas 5B', type: 'Infaq', amount: 150000, issuedAt: '2026-11-01', dueAt: '2026-11-30', status: 'Belum Dibayar', note: 'Infaq bulanan' },
    { student: 'Muhammad Rizki — Kelas 5B', type: 'Uang Buku', amount: 85000, issuedAt: '2026-11-01', dueAt: '2026-12-10', status: 'Menunggu Verifikasi', note: 'Buku semester' },
    { student: 'Muhammad Rizki — Kelas 5B', type: 'Wisata', amount: 200000, issuedAt: '2026-11-01', dueAt: '2026-12-20', status: 'Lunas', note: 'Kegiatan akhir semester' },
    { student: 'Muhammad Rizki — Kelas 5B', type: 'Seragam', amount: 175000, issuedAt: '2026-11-01', dueAt: '2026-11-15', status: 'Jatuh Tempo', note: 'Seragam olahraga' },
    { student: 'Muhammad Rizki — Kelas 5B', type: 'Tagihan Lainnya', amount: 50000, issuedAt: '2026-11-01', dueAt: '2026-12-30', status: 'Belum Dibayar', note: 'Iuran kegiatan' },
]);
const starterStudents = [
    { number: '1', name: 'AANG BAZUL ASYHAB', nisn: '3186263592', nik: '3203281607180007', birthPlace: 'CIANJUR', birthDate: '2018-07-16', levelRombel: 'Kelas 2 - 01', grade: '2', rombel: '01', age: '8 th, 0 bln', status: 'Aktif', gender: 'Laki-laki', address: 'KP. BELAKANG KULON RT 002/005 CIPANAS, CIPANAS, KABUPATEN CIANJUR, JAWA BARAT, 43253, 43253', phone: '', specialNeeds: 'Tidak Ada', disability: 'Tidak Ada', kip: '', father: 'MOCH FIRMAN SYAHID', mother: 'NURHASANAH', guardian: 'MOCH FIRMAN SYAHID' },
    { number: '2', name: 'ALIFAH NASHA SETIAWAN', nisn: '3197460008', nik: '3203285511190002', birthPlace: 'CIANJUR', birthDate: '2019-11-15', levelRombel: 'Kelas 2 - 01', grade: '2', rombel: '01', age: '6 th, 8 bln', status: 'Aktif', gender: 'Perempuan', address: 'KP. PARABON RT 002/003 CILOTO, CIPANAS, KABUPATEN CIANJUR, JAWA BARAT, 43253, 43253', phone: '', specialNeeds: 'Tidak Ada', disability: 'Tidak Ada', kip: '', father: 'H. USEP SETIAWAN, SH', mother: 'DWIDA AGUSTINA SUHERMAN', guardian: 'H. USEP SETIAWAN, SH' },
    { number: '3', name: 'ALISYA PUTRI RAMADANI', nisn: '3181126135', nik: '3203286905180005', birthPlace: 'CIANJUR', birthDate: '2018-05-29', levelRombel: 'Kelas 2 - 01', grade: '2', rombel: '01', age: '8 th, 1 bln', status: 'Aktif', gender: 'Perempuan', address: 'KP. NEGLASARI RT 001/002 CIPANAS, CIPANAS, KABUPATEN CIANJUR, JAWA BARAT, 43255, 43255', phone: '', specialNeeds: 'Tidak Ada', disability: 'Tidak Ada', kip: '', father: 'JAENAL SODIKIN', mother: 'ERNAWATI FEBIOLA SE', guardian: 'JAENAL SODIKIN' },
];
const savedStudents = localStorage.getItem('sis-demo-students');
if (!savedStudents) write('students', starterStudents);
else {
    const existingStudents = JSON.parse(savedStudents);
    const oldDemoNisn = new Set(['0012345678', '0012345679', '0012345680']);
    if (existingStudents.length === 3 && existingStudents.every(student => oldDemoNisn.has(String(student.nisn)))) write('students', starterStudents);
}

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
    list.innerHTML = items.map((item, index) => `<tr><td>${index + 1}</td><td>${escapeHtml(item.title)}<small class="announcement-table-description">${escapeHtml(item.body)}</small></td><td>${escapeHtml(item.publishedAt)}</td><td>${escapeHtml(item.attachment || 'Tanpa lampiran')}</td><td><a class="keuangan-button" href="/keuangan/pengumuman/${index}/edit">Edit</a></td></tr>`).join('');
}
function currentStudentInvoices() {
    return Array.isArray(window.studentInvoices) ? window.studentInvoices : [];
}
function renderStudentInvoices() {
    const body = document.querySelector('[data-student-invoices]'); if (!body) return;
    // Data tagihan siswa disediakan backend untuk siswa yang sedang login; jangan tampilkan fixture keuangan demo sebagai tagihan nyata.
    let items = currentStudentInvoices();
    const selectors = document.querySelectorAll('.spp-filter-card select');
    const category = selectors[1]?.value; const status = selectors[2]?.value;
    if (category && category !== 'all') items = items.filter(item => category === 'Lainnya' ? !['Infaq', 'Uang Buku', 'Wisata', 'Seragam'].includes(item.type) : item.type === category);
    if (status && status !== 'all') items = items.filter(item => item.status === ({ lunas: 'Lunas', belum: 'Belum Dibayar', pending: 'Menunggu Verifikasi', overdue: 'Jatuh Tempo' }[status] || status));
    body.innerHTML = items.length ? items.map(item => `<tr><td>${escapeHtml(item.type)}</td><td>Rp ${Number(item.amount).toLocaleString('id-ID')}</td><td>${escapeHtml(item.dueAt)}</td><td>${escapeHtml(item.status)}</td><td><button type="button" class="keuangan-button" data-invoice-detail="${escapeHtml(item.note || item.type)}">Lihat Detail</button></td></tr>`).join('') : `<tr><td colspan="5" class="spp-table-empty-container">Belum ada data tagihan untuk periode ini.</td></tr>`;
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
    const items = read('announcements');
    target.innerHTML = items.length ? items.map(item => `<article><h4>${escapeHtml(item.title)}</h4><p>${escapeHtml(item.body)}</p><small>${escapeHtml(item.publishedAt)} · ${escapeHtml(item.attachment || 'Tanpa lampiran')}</small></article>`).join('') : `<div class="shared-announcement-empty shared-announcement-empty-compact"><span class="shared-announcement-icon" aria-hidden="true">📣</span><strong>Belum Ada Informasi Keuangan</strong><p>Pengumuman keuangan madrasah akan tampil di sini.</p></div>`;
}
let studentPage = 1;
function renderOperatorStudents() {
    const body = document.querySelector('[data-operator-students]'); if (!body) return;
    const form = document.querySelector('.operator-student-filters'); const query = form?.querySelector('input[type="search"]').value.toLowerCase() || '';
    const grade = form?.querySelector('[data-student-filter="kelas"]').value || ''; const rombel = form?.querySelector('[data-student-filter="rombel"]').value || '';
    const students = read('students').map((student, id) => ({ ...student, id })).filter(student => (!query || `${student.name} ${student.nisn}`.toLowerCase().includes(query)) && (!grade || student.grade === grade) && (!rombel || student.rombel === rombel));
    const pageSize = 15, pageCount = Math.max(1, Math.ceil(students.length / pageSize));
    studentPage = Math.min(studentPage, pageCount);
    const visibleStudents = students.slice((studentPage - 1) * pageSize, studentPage * pageSize);
    body.innerHTML = visibleStudents.map(student => `<tr><td>${escapeHtml(student.id + 1)}</td><td>${escapeHtml(student.name || '—')}</td><td>${escapeHtml(student.nisn || '—')}</td><td>${escapeHtml(student.nik || '—')}</td><td>${escapeHtml(student.birthPlace || '—')}</td><td>${escapeHtml(student.birthDate || '—')}</td><td>${escapeHtml(student.levelRombel || (student.grade ? `Kelas ${student.grade}${student.rombel ? ` - ${student.rombel}` : ''}` : '—'))}</td><td>${escapeHtml(student.age || '—')}</td><td>${escapeHtml(student.status || '—')}</td><td>${escapeHtml(student.gender || '—')}</td><td>${escapeHtml(student.address || '—')}</td><td>${escapeHtml(student.phone || '—')}</td><td>${escapeHtml(student.specialNeeds || '—')}</td><td>${escapeHtml(student.disability || '—')}</td><td>${escapeHtml(student.kip || '—')}</td><td>${escapeHtml(student.father || '—')}</td><td>${escapeHtml(student.mother || '—')}</td><td>${escapeHtml(student.guardian || '—')}</td><td><a href="/operator/data-siswa/${student.id}/edit">Edit</a></td></tr>`).join('') || '<tr><td colspan="19">Tidak ada siswa untuk filter yang dipilih.</td></tr>';
    const total = read('students');
    const visibleCount = document.querySelector('[data-visible-students]'); const totalCount = document.querySelector('[data-total-students]');
    if (visibleCount) visibleCount.textContent = visibleStudents.length;
    if (totalCount) totalCount.textContent = total.length;
    document.querySelector('[data-student-page]').textContent = studentPage;
    document.querySelector('[data-student-prev]').disabled = studentPage <= 1;
    document.querySelector('[data-student-next]').disabled = studentPage >= pageCount;
    document.querySelectorAll('.operator-student-stats article strong').forEach((stat, index) => {
        const count = index === 0 ? total.length : total.filter(student => index === 1 ? student.gender === 'Laki-laki' : student.gender === 'Perempuan').length;
        stat.textContent = `${count} ${index === 2 ? 'Siswi' : 'Siswa'}`;
    });
}
let teacherPage = 1;
function renderOperatorTeachers() {
    const body = document.querySelector('[data-operator-teachers]');
    if (!body) return;
    const form = document.querySelector('.operator-teacher-filters');
    const query = form?.querySelector('input[type="search"]')?.value.trim().toLowerCase() || '';
    const all = JSON.parse(localStorage.getItem('sis-demo-teachers') || '[]');
    const teachers = all.map((teacher, id) => ({ ...teacher, id })).filter(teacher => !query || `${teacher.name} ${teacher.nip} ${teacher.nuptk}`.toLowerCase().includes(query));
    const pageSize = 15, pageCount = Math.max(1, Math.ceil(teachers.length / pageSize));
    teacherPage = Math.min(teacherPage, pageCount);
    const visibleTeachers = teachers.slice((teacherPage - 1) * pageSize, teacherPage * pageSize);
    body.innerHTML = visibleTeachers.map(teacher => `<tr><td>${teacher.id + 1}</td><td>${escapeHtml(teacher.name || '—')}</td><td>${escapeHtml(teacher.nik || '—')}</td><td>${escapeHtml(teacher.nuptk || '—')}</td><td>${escapeHtml(teacher.status || '—')}</td><td>${escapeHtml(teacher.nip || '—')}</td><td>${escapeHtml(teacher.gender || '—')}</td><td>${escapeHtml(teacher.birthPlace || '—')}</td><td>${escapeHtml(teacher.birthDate || '—')}</td><td>${escapeHtml(teacher.phone || '—')}</td><td>${escapeHtml(teacher.email || '—')}</td><td>${escapeHtml(teacher.accountEmail || '—')}</td><td>${escapeHtml(teacher.task || '—')}</td><td>${escapeHtml(teacher.subject || '—')}</td><td>${escapeHtml(teacher.placement || '—')}</td><td>${escapeHtml(teacher.jtm || '—')}</td><td>—</td></tr>`).join('') || '<tr><td colspan="17">Belum ada data guru.</td></tr>';
    document.querySelector('[data-teacher-visible]').textContent = visibleTeachers.length;
    document.querySelector('[data-teacher-total]').textContent = all.length;
    document.querySelector('[data-teacher-page]').textContent = teacherPage;
    document.querySelector('[data-teacher-prev]').disabled = teacherPage <= 1;
    document.querySelector('[data-teacher-next]').disabled = teacherPage >= pageCount;
    const stats = document.querySelectorAll('.operator-teacher-stats article strong');
    if (stats[0]) stats[0].textContent = all.length;
    if (stats[1]) stats[1].textContent = all.filter(teacher => teacher.task && /wali kelas/i.test(teacher.task)).length;
}
renderOperatorTeachers();
document.querySelectorAll('.operator-teacher-filters').forEach(form => {
    form.addEventListener('input', () => { teacherPage = 1; renderOperatorTeachers(); });
    form.addEventListener('submit', event => { event.preventDefault(); teacherPage = 1; renderOperatorTeachers(); });
    form.addEventListener('reset', () => setTimeout(() => { teacherPage = 1; renderOperatorTeachers(); }));
});
document.querySelector('[data-teacher-prev]')?.addEventListener('click', () => { teacherPage--; renderOperatorTeachers(); });
document.querySelector('[data-teacher-next]')?.addEventListener('click', () => { teacherPage++; renderOperatorTeachers(); });
function renderTeacherAttendance() {
    const body = document.querySelector('[data-teacher-attendance]'); if (!body) return;
    const rows = [
        { name: 'Ahmad Fauzi, S.Pd.I', present: 20, leave: 1, sick: 0, absent: 0, month: 'November', year: '2026' },
        { name: 'Siti Rahmawati, S.Pd.', present: 19, leave: 0, sick: 1, absent: 1, month: 'November', year: '2026' },
        { name: 'Dedi Kurniawan, S.Pd.I', present: 18, leave: 1, sick: 1, absent: 1, month: 'November', year: '2026' },
    ];
    const form = document.querySelector('[data-attendance-filters]');
    const search = form.elements.search.value.trim().toLowerCase();
    const month = form.elements.month.value;
    const year = form.elements.year.value;
    const filtered = rows.filter(row => row.name.toLowerCase().includes(search) && (!month || row.month === month) && (!year || row.year === year));
    body.innerHTML = filtered.length ? filtered.map(row => `<tr><td>${escapeHtml(row.name)}</td><td>${row.present}</td><td>${row.leave}</td><td>${row.sick}</td><td>${row.absent}</td><td><button type="button" class="keuangan-button" data-attendance-detail="${escapeHtml(row.name)}">Detail</button></td></tr>`).join('') : '<tr><td colspan="6" class="attendance-empty-cell">Tidak ada data absensi untuk filter ini.</td></tr>';
    body.querySelectorAll('[data-attendance-detail]').forEach(button => button.addEventListener('click', () => alert(`Rekap absensi ${button.dataset.attendanceDetail}: hadir, izin, sakit, dan tanpa keterangan.`)));
}
renderInvoices(); renderAnnouncements(); renderStudentInvoices(); populatePaymentInvoices(); renderOperatorStudents();
renderKeuanganRows(); renderStudentAnnouncements(); renderTeacherAttendance();
document.querySelectorAll('[data-dashboard-bills]').forEach(el => { const pending = currentStudentInvoices().filter(item => ['Belum Dibayar', 'Jatuh Tempo'].includes(item.status)); el.innerHTML = pending.length ? `🔔 <strong>${pending.length} tagihan belum dibayar</strong>: ${pending.map(item => `${escapeHtml(item.type)} Rp ${Number(item.amount).toLocaleString('id-ID')} (jatuh tempo ${escapeHtml(item.dueAt)})`).join('; ')}. <a href="/siswa/tagihan-spp">Lihat detail</a>` : 'Tidak ada tagihan yang belum dibayar.'; });
document.querySelectorAll('[data-bill-count]').forEach(el => { const count = currentStudentInvoices().filter(item => ['Belum Dibayar', 'Jatuh Tempo'].includes(item.status)).length; el.textContent = count; el.hidden = !count; });
document.querySelectorAll('[data-keuangan-filters]').forEach(form => { form.addEventListener('input', renderKeuanganRows); form.addEventListener('change', renderKeuanganRows); form.addEventListener('reset', () => setTimeout(renderKeuanganRows)); });
document.querySelectorAll('[data-attendance-filters]').forEach(form => form.addEventListener('submit', event => { event.preventDefault(); renderTeacherAttendance(); }));
document.querySelectorAll('.operator-student-filters').forEach(form => { form.addEventListener('input', () => { studentPage = 1; renderOperatorStudents(); }); form.addEventListener('change', () => { studentPage = 1; renderOperatorStudents(); }); form.addEventListener('reset', () => setTimeout(() => { studentPage = 1; renderOperatorStudents(); })); });
document.querySelector('[data-student-prev]')?.addEventListener('click', () => { studentPage--; renderOperatorStudents(); });
document.querySelector('[data-student-next]')?.addEventListener('click', () => { studentPage++; renderOperatorStudents(); });
document.querySelectorAll('.spp-filter-card select').forEach(select => select.addEventListener('change', renderStudentInvoices));
document.querySelectorAll('[data-apply-student-filters]').forEach(button => button.addEventListener('click', renderStudentInvoices));

// Keep announcement summaries and filters in sync with the browser demo data.
function renderFilteredAnnouncements() {
    const list = document.querySelector('[data-announcement-list]');
    if (!list) return;
    const all = read('announcements');
    const controls = document.querySelector('[data-shared-announcement-controls][data-announcement-source="finance"]');
    const form = controls?.querySelector('[data-announcement-filter-form]');
    const search = form?.elements.search.value.trim().toLowerCase() || '';
    const category = form?.elements.category.value || '';
    const status = form?.elements.status.value || '';
    const tab = controls?.querySelector('[data-announcement-tab].active')?.dataset.announcementTab || 'all';
    let items = all.map((item, index) => ({ ...item, index }));
    if (search) items = items.filter(item => `${item.title} ${item.body}`.toLowerCase().includes(search));
    if (category) items = items.filter(item => (item.category || 'Informasi Umum') === category);
    if (status && status !== 'Terbit') items = [];
    if (['draft', 'archive'].includes(tab)) items = [];
    list.innerHTML = items.length
        ? items.map((item, index) => `<tr><td>${index + 1}</td><td>${escapeHtml(item.title)}<small class="announcement-table-description">${escapeHtml(item.body)}</small></td><td>${escapeHtml(item.publishedAt)}</td><td>${escapeHtml(item.attachment || 'Tanpa lampiran')}</td><td><a class="keuangan-button" href="/keuangan/pengumuman/${item.index}/edit">Edit</a></td></tr>`).join('')
        : `<tr><td colspan="5"><div class="shared-announcement-empty shared-announcement-empty-compact"><strong>${all.length ? 'Tidak ada pengumuman yang cocok' : 'Belum Ada Pengumuman Keuangan'}</strong><p>${all.length ? 'Ubah kata kunci atau filter untuk melihat hasil lainnya.' : 'Informasi pembayaran dan tagihan dari madrasah akan ditampilkan di sini.'}</p></div></td></tr>`;
    controls?.querySelectorAll('[data-announcement-stat="total"], [data-announcement-stat="active"]').forEach(node => { node.textContent = all.length; });
    controls?.querySelectorAll('[data-announcement-tab-count]').forEach(node => { node.textContent = all.length; });
    const visible = document.querySelector('[data-announcement-visible]');
    const total = document.querySelector('[data-announcement-total]');
    if (visible) visible.textContent = items.length;
    if (total) total.textContent = all.length;
}

function renderFilteredStudentAnnouncements() {
    const target = document.querySelector('[data-finance-announcements]');
    if (!target) return;
    const all = read('announcements');
    const controls = document.querySelector('[data-shared-announcement-controls][data-announcement-source="finance"]');
    const form = controls?.querySelector('[data-announcement-filter-form]');
    const search = form?.elements.search.value.trim().toLowerCase() || '';
    const category = form?.elements.category.value || '';
    const tab = controls?.querySelector('[data-announcement-tab].active')?.dataset.announcementTab || 'all';
    let items = all;
    if (search) items = items.filter(item => `${item.title} ${item.body}`.toLowerCase().includes(search));
    if (category) items = items.filter(item => (item.category || 'Informasi Umum') === category);
    if (tab === 'academic') items = items.filter(item => item.category === 'Akademik');
    if (tab === 'student') items = items.filter(item => item.category === 'Kesiswaan');
    if (tab === 'finance') items = items.filter(item => ['Tagihan', 'Pembayaran', 'Keuangan'].includes(item.category));
    target.innerHTML = items.length
        ? items.map(item => `<article><h4>${escapeHtml(item.title)}</h4><p>${escapeHtml(item.body)}</p><small>${escapeHtml(item.publishedAt)} · ${escapeHtml(item.attachment || 'Tanpa lampiran')}</small></article>`).join('')
        : `<div class="shared-announcement-empty shared-announcement-empty-compact"><strong>${all.length ? 'Tidak ada pengumuman yang cocok' : 'Belum Ada Informasi Keuangan'}</strong><p>${all.length ? 'Ubah kata kunci atau filter untuk melihat informasi lainnya.' : 'Pengumuman keuangan madrasah akan tampil di sini.'}</p></div>`;
    controls?.querySelectorAll('[data-announcement-stat="total"], [data-announcement-stat="active"], [data-announcement-stat="finance"]').forEach(node => { node.textContent = all.length; });
    controls?.querySelectorAll('[data-announcement-tab-count]').forEach(node => { node.textContent = all.length; });
}

document.querySelectorAll('[data-shared-announcement-controls]').forEach(controls => {
    controls.querySelectorAll('[data-announcement-tab]').forEach(button => button.addEventListener('click', () => {
        controls.querySelectorAll('[data-announcement-tab]').forEach(tab => { tab.classList.toggle('active', tab === button); tab.setAttribute('aria-pressed', String(tab === button)); });
        renderFilteredAnnouncements();
        renderFilteredStudentAnnouncements();
    }));
    controls.querySelector('[data-announcement-filter-form]')?.addEventListener('submit', event => {
        event.preventDefault();
        renderFilteredAnnouncements();
        renderFilteredStudentAnnouncements();
    });
});
renderFilteredAnnouncements();
renderFilteredStudentAnnouncements();
