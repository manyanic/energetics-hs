import barba from '@barba/core'
import axios from 'axios'
import loadMedia from './lib/lazy-loading.js'

class Barba {
  constructor () {
    this.initBarba()
    this.loadImages()
  }

  initBarba () {
    barba.init({
      debug: false,
      cacheIgnore: false,
      prefetchIgnore: false,
      timeout: 5000,
      transitions: [
        {
          name: 'default',
          leave ({ current, next, trigger }) {
            current.container.classList.remove('barba-container--fadein')
            current.container.classList.toggle('barba-container--fadeout')
            setTimeout(() => {
              this.async()
            }, 400)
          },
          enter ({ current, next, trigger }) {
            window.scrollTo(0, 0)
            next.container.classList.remove('barba-container--fadeout')
            next.container.classList.toggle('barba-container--fadein')
          }
        }
      ],
      prevent: ({ el }) => {
        const ignoreClasses = ['ab-item', 'another-class-here'] // Additional (besides .prevent) ignore links with these classes (ab-item is for wp admin toolbar links)
        if (/.pdf/.test(el.href.toLowerCase())) {
          return true
        }
        if (el.classList && el.classList.contains('prevent')) {
          return true
        }
        if (el.dataset && el.dataset.lightbox) {
          return true
        }
        for (let i = 0; i < ignoreClasses.length; i++) {
          if (el.classList.contains(ignoreClasses[i])) {
            return true
          }
        }
      },
      requestError: (trigger, action, url, response) => {
        console.log('REQUEST ERROR')
        console.log(url)
        console.log(response)
      }
    })
    // Global enter hook
    barba.hooks.enter(({ current, next, trigger }) => {
      window.scrollTo(0, 0)
      window.requestAnimationFrame(() => {
        this.setBodyClasses(next.container)
        this.setTranslationLinks(next.container)
        this.mobileMenu()
        this.updateCurrentHighlightedMenu(next.html)
        this.fixWpAdminBar()
        window.templatePartsLoader.fetchTemplatePartsFromHtml(next.html)
        // Google Analytics
        if (typeof window.ga === 'function') {
          window.ga('send', 'pageview', window.location.pathname)
        }
      })
    })
    window.templatePartsLoader.fetchTemplatePartsFromHtml(document)
  }

  loadImages () {
    const imagesLoaded = false
    window.onscroll = function (e) {
      if (imagesLoaded === false) {
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
          loadMedia(null, null, true)
        }
      }
    }
  }

  setBodyClasses (e) {
    const skipClasses = ['no-cursor']
    const i = e.querySelector('#body-classes').value
    const o = i.split(',')
    const currentBodyClasses = document.querySelector('body').classList
    Array.from(currentBodyClasses).forEach(bodyClass => {
      // Don't remove class if it exists in skipClasses
      if (!skipClasses.includes(bodyClass)) {
        currentBodyClasses.remove(bodyClass)
      }
    })
    for (let s = 0; s < o.length; s++) {
      document.querySelector('body').classList.add(o[s])
    }
  }

  setTranslationLinks (e) {
    'use strict'
    const i = e.querySelector('#translation-links').value
    const o = i.split(',')
    for (let s = 0; s < o.length; s++) {
      document.querySelector('.header__languages ul li:nth-child(' + (s + 1) + ') a').setAttribute('href', o[s])
    }
  }

  mobileMenu () {
    const menuForMobile = document.querySelector('.menu_for_mobile')
    if (menuForMobile) menuForMobile.classList.remove('active')
    const menuMobile = document.querySelector('.menu_mobile')
    if (menuMobile) menuMobile.classList.remove('on')
  }

  /**
   * Parses the DOM structure of the BarbaJS fetch, and applies same classes on the header in DOM.
   * @param {String} htmlString - The new page html in string format from barbaJs
   */
  updateCurrentHighlightedMenu (htmlString) {
    const html = new window.DOMParser().parseFromString(htmlString, 'text/html')
    if ('body' in html === false || html.body.childNodes.length < 1) return
    const newSelectedMenuItemId = html.querySelector('.current-menu-item')
    const newSelectedAncestorId = html.querySelector('.current-menu-ancestor')
    const newSelectedParentId = html.querySelector('.current-menu-parent')
    const newSelectedSubMenuId = html.querySelector('.sub-menu > .current-menu-item')
    // First remove current classes from all menu items and sub menus
    const menuItems = Array.from(document.querySelectorAll('.menu-item'))
    for (const el of menuItems) {
      el.classList.remove('current-menu-item')
      el.classList.remove('current-menu-parent')
      el.classList.remove('current-menu-ancestor')
    }
    if (newSelectedMenuItemId) document.querySelector(`#${newSelectedMenuItemId.id}`).classList.add('current-menu-item')
    if (newSelectedAncestorId) document.querySelector(`#${newSelectedAncestorId.id}`).classList.add('current-menu-ancestor')
    if (newSelectedParentId) document.querySelector(`#${newSelectedParentId.id}`).classList.add('current-menu-parent')
    if (newSelectedSubMenuId) document.querySelector(`#${newSelectedSubMenuId.id}`).classList.add('current-menu-item')
  }

  fixWpAdminBar () {
    if (!document.querySelector('#wpadminbar')) {
      return
    }
    const bodyFormData = new window.FormData()
    bodyFormData.set('action', 'hs_fix_barbajs_wp_admin')
    bodyFormData.set('location', window.location.href)
    axios.post(window.scripts_ajax_variables.ajax_url, bodyFormData, { headers: { 'Content-Type': 'multipart/form-data' } }).then(response => {
      if (!response.data) {
        return
      }
      if (response.data && response.data.success === true) {
        const adminMenuLinks = Array.from(document.querySelector('.ab-top-menu').querySelectorAll('a'))
        for (const adminLink of adminMenuLinks) {
          const mregex = /post=(\d+)&/g
          adminLink.classList.add('prevent')
          const href = adminLink.getAttribute('href')
          const groups = mregex.exec(href)
          if (groups && groups.length >= 2) {
            adminLink.setAttribute('href', href.replace(groups[1], response.data.pageId))
          } else {
            const chunks = href.split('?url=')
            if (chunks.length === 2) {
              adminLink.setAttribute('href', chunks[0] + '?url=' + encodeURIComponent(window.location.href))
            }
          }
        }
      }
    }).catch(error => {
      console.warn('Error calling admin-ajax: ')
      console.warn(error)
    })
  }
}

export default Barba
