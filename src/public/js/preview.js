const input = document.querySelector('input[name="image_url"]')
const figure = document.querySelector('#figure')
const figureImage = document.querySelector('#figureImage')

input.addEventListener('change', (event) => {
    const [file] = event.target.files
    const fileReader = new FileReader();

    fileReader.readAsDataURL(file);
    if (file) {
        figureImage.setAttribute('src', URL.createObjectURL(file))
        figure.style.display = 'block'
    } else {
        figure.style.display = 'none'
    }
})
