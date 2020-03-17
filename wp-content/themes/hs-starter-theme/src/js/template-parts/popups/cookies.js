import '../../lib/scrollingElement'

class Cookies {
  constructor () {
    this.cookiesAccept = false
    this.unlocked = false
    this.checkCookies()
    window.addEventListener('scroll', this.scroll)
    if (this.cookiesAccept === true) {
      this.showCookiesBar()
    }
  }

  getCookie (cname) {
    var name = cname + '='
    var decodedCookie = decodeURIComponent(document.cookie)
    var ca = decodedCookie.split(';')
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i]
      while (c.charAt(0) === ' ') {
        c = c.substring(1)
      }
      if (c.indexOf(name) === 0) {
        return c.substring(name.length, c.length)
      }
    }
    return ''
  }

  setCookie (cname, cvalue, exdays) {
    var d = new Date()
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000))
    var expires = 'expires=' + d.toUTCString()
    document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/'
  }

  checkCookies () {
    var cookie = this.getCookie('legal')
    if (!cookie) {
      this.cookiesAccept = true
    }
  }

  scroll () {
    if (this.unlocked) {
      window.removeEventListener('scroll', this.scroll)
    } else {
      if (document.hasOwnProperty.call('scrollingElement') && document.scrollingElement.scrollTop > 100) {
        this.unlocked = true
        this.setCookie('legal', 'ok', 1000)
        this.cookiesAccept = false
        this.hideCookiesBar()
      }
    }
  }

  showCookiesBar () {
    document.getElementById('cookies-advice').classList.add('cookies-advice--active')
  }

  hideCookiesBar () {
    document.getElementById('cookies-advice').classList.remove('cookies-advice--active')
  }

  acceptCookies () {
    this.setCookie('legal', 'ok', 1000)
    this.cookiesAccept = !this.cookiesAccept
    window.removeEventListener('scroll', this.scroll)
    this.hideCookiesBar()
  }
}

export default Cookies
