<section class="shared-announcement-controls" data-shared-announcement-controls data-announcement-source="{{ $announcementSource ?? (request()->is('keuangan/pengumuman*') || request()->is('siswa/dashboard') ? 'finance' : 'school') }}" aria-label="Ringkasan dan filter pengumuman">
    <div class="shared-announcement-stats">
        @foreach ($announcementStats as $stat)
            <article><span>{{ $stat['label'] }}</span><strong data-announcement-stat="{{ $stat['key'] }}">{{ $stat['value'] }}</strong><i aria-hidden="true">{{ $loop->first ? '◉' : ($loop->last ? '▤' : '✓') }}</i></article>
        @endforeach
    </div>
    <nav class="shared-announcement-tabs" aria-label="Kategori pengumuman">
        @foreach ($announcementTabs as $tab)
            <button type="button" class="{{ $loop->first ? 'active' : '' }}" data-announcement-tab="{{ $tab['value'] }}">{{ $tab['label'] }} (<span data-announcement-tab-count>{{ $tab['count'] ?? 0 }}</span>)</button>
        @endforeach
    </nav>
    <form class="shared-announcement-filters" data-announcement-filter-form>
        <label class="shared-announcement-search"><span class="sr-only">Cari pengumuman</span><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input type="search" name="search" placeholder="{{ $announcementSearch ?? 'Cari judul pengumuman atau kata kunci' }}"></label>
        <label><span class="sr-only">Kategori</span><select name="category" aria-label="Filter kategori"><option value="">{{ $announcementCategoryLabel ?? 'Semua Kategori' }}</option>@foreach ($announcementCategories as $category)<option value="{{ $category }}">{{ $category }}</option>@endforeach</select></label>
        <label><span class="sr-only">Status</span><select name="status" aria-label="Filter status"><option value="">Semua Status</option>@foreach (($announcementStatuses ?? ['Terbit']) as $status)<option value="{{ $status }}">{{ $status }}</option>@endforeach</select></label>
        <button type="submit" class="shared-announcement-filter-button"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5h16l-6.5 7.5V19l-3 1v-7.5L4 5Z"/></svg>Terapkan Filter</button>
    </form>
</section>
