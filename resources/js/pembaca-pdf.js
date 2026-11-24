import { AnnotationMode, getDocument, GlobalWorkerOptions } from 'pdfjs-dist';
import workerUrl from 'pdfjs-dist/build/pdf.worker.min.mjs?url';

GlobalWorkerOptions.workerSrc = workerUrl;

const reader = document.querySelector('[data-pdf-url]');
const canvas = reader.querySelector('[data-pdf-canvas]');
const status = reader.querySelector('[data-pdf-status]');
const pageLabel = reader.querySelector('[data-pdf-page]');
const previous = reader.querySelector('[data-pdf-prev]');
const next = reader.querySelector('[data-pdf-next]');
const zoomOut = reader.querySelector('[data-pdf-zoom-out]');
const zoomIn = reader.querySelector('[data-pdf-zoom-in]');
const controls = [previous, next, zoomOut, zoomIn];
let pdf;
let pageNumber = 1;
let zoom = 100;

async function renderPage() {
    for (const control of controls) control.disabled = true;

    try {
        const page = await pdf.getPage(pageNumber);
        const initial = page.getViewport({ scale: 1 });
        const width = Math.min(760, canvas.parentElement.clientWidth);
        const viewport = page.getViewport({ scale: width / initial.width * zoom / 100 });
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
        pageLabel.textContent = `Halaman ${pageNumber} dari ${pdf.numPages} · ${zoom}%`;
    } catch {
        canvas.hidden = true;
        status.textContent = 'Halaman PDF tidak dapat ditampilkan.';
        status.hidden = false;
    } finally {
        previous.disabled = pageNumber <= 1;
        next.disabled = pageNumber >= pdf.numPages;
        zoomOut.disabled = zoom <= 75;
        zoomIn.disabled = zoom >= 200;
    }
}

previous.addEventListener('click', () => { pageNumber -= 1; renderPage(); });
next.addEventListener('click', () => { pageNumber += 1; renderPage(); });
zoomOut.addEventListener('click', () => { zoom -= 25; renderPage(); });
zoomIn.addEventListener('click', () => { zoom += 25; renderPage(); });

getDocument({ url: reader.dataset.pdfUrl, isEvalSupported: false }).promise
    .then((document) => {
        pdf = document;
        return renderPage();
    })
    .catch(() => {
        status.textContent = 'Naskah PDF tidak dapat dibuka.';
    });
