//  import './scss/style.scss';  Comment / Uncomment depending if you use css or scss
import './css/style.css'
//  import Barba from './js/barba.js'
import Cookies from './js/template-parts/popups/cookies.js'
import TemplatePartsLoader from './js/template-parts-loader.js'
import Slider from './js/template-parts/sections/slider.js'

window.addEventListener('load', function (event) {
  // Cookies banner
  window.cookies = new Cookies()

  // Load necessary Template Parts scripts
  window.templatePartsLoader = new TemplatePartsLoader()
  const templatePartsLoader = window.templatePartsLoader
  templatePartsLoader.addTemplatePart(new Slider(), 'Slider')

  // BarbaJS
  //  window.barba = new Barba()
})
