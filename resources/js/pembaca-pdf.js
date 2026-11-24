import { AnnotationMode, getDocument, GlobalWorkerOptions } from 'pdfjs-dist';
import workerUrl from 'pdfjs-dist/build/pdf.worker.min.mjs?worker&url';

GlobalWorkerOptions.workerSrc = workerUrl;

const reader = document.querySelector('[data-pdf-url]');
const stage = reader.querySelector('.digital-reader-pdf-page');
const canvas = reader.querySelector('[data-pdf-canvas]');
const status = reader.querySelector('[data-pdf-status]');
const pageLabel = reader.querySelector('[data-pdf-page]');
const pageTotal = reader.querySelector('[data-pdf-total]');
const picker = reader.querySelector('[data-pdf-picker]');
const target = reader.querySelector('[data-pdf-target]');
const previous = reader.querySelector('[data-pdf-prev]');
const next = reader.querySelector('[data-pdf-next]');
const zoomOut = reader.querySelector('[data-pdf-zoom-out]');
const zoomIn = reader.querySelector('[data-pdf-zoom-in]');
const zoomLabel = reader.querySelector('[data-pdf-zoom]');
const portrait = window.matchMedia('(max-width: 42rem)');
const controls = [previous, next, zoomOut, zoomIn, target];

let pdf;
let pageNumber = 1;
let zoom = 100;
let rendering = false;
let rerender = false;

function refreshControls() {
    previous.disabled = pageNumber <= 1;
    next.disabled = pageNumber >= pdf.numPages;
    zoomOut.disabled = zoom <= 75;
    zoomIn.disabled = zoom >= 200;
    target.disabled = false;
    target.max = pdf.numPages;
    target.value = pageNumber;
    pageTotal.textContent = pdf.numPages;
    pageLabel.textContent = `Halaman ${pageNumber} dari ${pdf.numPages}`;
    zoomLabel.textContent = `${zoom}%`;
}

async function renderPage() {
    if (!pdf) return;

    if (rendering) {
        rerender = true;
        return;
    }

    rendering = true;
    for (const control of controls) control.disabled = true;

    try {
        const page = await pdf.getPage(pageNumber);
        const natural = page.getViewport({ scale: 1 });
        const padding = getComputedStyle(stage);
        const width = Math.max(1, stage.clientWidth - parseFloat(padding.paddingLeft) - parseFloat(padding.paddingRight));
        const height = Math.max(1, stage.offsetHeight - parseFloat(padding.paddingTop) - parseFloat(padding.paddingBottom) - 2);
        const fitWidth = Math.min(760, width) / natural.width;
        const fit = portrait.matches ? fitWidth : Math.min(fitWidth, height / natural.height);
        const viewport = page.getViewport({ scale: fit * zoom / 100 });
        const pixelRatio = Math.min(window.devicePixelRatio || 1, 2);

        canvas.width = Math.ceil(viewport.width * pixelRatio);
        canvas.height = Math.ceil(viewport.height * pixelRatio);
        canvas.style.width = `${viewport.width}px`;
        canvas.style.height = `${viewport.height}px`;

        await page.render({
            canvasContext: canvas.getContext('2d'),
            viewport,
            transform: [pixelRatio, 0, 0, pixelRatio, 0, 0],
            annotationMode: AnnotationMode.DISABLE,
        }).promise;

        canvas.hidden = false;
        status.hidden = true;
    } catch {
        canvas.hidden = true;
        status.textContent = 'Halaman PDF tidak dapat ditampilkan.';
        status.hidden = false;
    } finally {
        rendering = false;

        if (rerender) {
            rerender = false;
            renderPage();
        } else {
            refreshControls();
        }
    }
}

function goToPage(number) {
    if (!pdf || rendering) return;

    if (Number.isInteger(number) && number >= 1 && number <= pdf.numPages && number !== pageNumber) {
        pageNumber = number;
        stage.scrollTo(0, 0);
        renderPage();
    }

    target.value = pageNumber;
}

previous.addEventListener('click', () => goToPage(pageNumber - 1));
next.addEventListener('click', () => goToPage(pageNumber + 1));
picker.addEventListener('submit', (event) => {
    event.preventDefault();
    goToPage(target.valueAsNumber);
});
zoomOut.addEventListener('click', () => { zoom -= 25; renderPage(); });
zoomIn.addEventListener('click', () => { zoom += 25; renderPage(); });
portrait.addEventListener('change', renderPage);
new ResizeObserver(renderPage).observe(stage);

getDocument({ url: reader.dataset.pdfUrl, isEvalSupported: false }).promise
    .then((document) => {
        pdf = document;
        return renderPage();
    })
    .catch(() => {
        status.textContent = 'Naskah PDF tidak dapat dibuka.';
    });
