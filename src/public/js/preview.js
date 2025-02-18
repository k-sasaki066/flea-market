document.addEventListener("DOMContentLoaded", function () {
    const figure = document.querySelector('#figure');
    const sellFigure = document.querySelector('#sellFigure');
    const figureImage = document.querySelector('#figureImage');
    const input = document.querySelector('input[name="image_url"]');

    if (!figureImage.getAttribute('src')) {
        figureImage.style.display = 'none';
    }

    if (input) {
        input.addEventListener('change', (event) => {
        const [file] = event.target.files;

        if (!file) {
            sellFigure.style.display = 'none';
            figureImage.style.display = 'none';
            figureImage.removeAttribute('src');
            return;
        }

        const fileReader = new FileReader();
        fileReader.onload = () => {
            figureImage.setAttribute('src', fileReader.result);
            figureImage.style.display = 'block';
            if (sellFigure) {
                sellFigure.style.display = 'block';
            }
        };
        fileReader.readAsDataURL(file);
        });
    }
});