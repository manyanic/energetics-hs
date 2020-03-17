/* eslint-disable no-unused-vars */
import upperFirst from 'lodash-es/upperFirst'
import camelCase from 'lodash-es/camelCase'

class TemplatePartsLoader {
  constructor () {
    this.templateParts = []
    this.runningTemplateParts = []
  }

  /**
   * Loads templateParts without initialising them.
   * @param {Object|Array} templatePartClass
   * @param templatePartName
   * @param {Object} options
   */
  addTemplatePart (templatePartClass, templatePartName = '', options = {}) {
    if (typeof templatePartClass === 'object') {
      window.templatePartsLoader.templateParts.push({ templatePartClass: templatePartClass, templatePartName: templatePartName, templatePartSettings: options })
    }
  }

  /**
   * Calls the destroy function on the templatePart class and removes it from this.runningTemplateParts
   * @param templatePartName
   * @returns {Promise<any>}
   */
  destroyTemplatePartInstance (templatePartName) {
    return new Promise((resolve, reject) => {
      try {
        const templatePartIndex = window.templatePartsLoader.runningTemplateParts.findIndex(mod => mod.templatePartName === templatePartName)
        if (templatePartIndex !== -1) {
          window.templatePartsLoader.runningTemplateParts[templatePartIndex]['templatePartInstance'].destroy()
          // Remove it from running templateParts
          window.templatePartsLoader.runningTemplateParts = window.templatePartsLoader.runningTemplateParts.filter(mod => mod.templatePartName !== templatePartName)
          resolve(templatePartName)
        }
        reject(new Error(`Could not destroy templatePart ${templatePartName}`))
      } catch (e) {
        reject(e)
      }
    })
  }

  /**
   * Creates a new instance of the loaded templatePart and inserts it in this.runningTemplateParts
   * @param templatePartName
   * @returns {Promise<any>}
   */
  initTemplatePartInstance (templatePartName) {
    // console.log('templatePartName:', templatePartName)
    return new Promise((resolve, reject) => {
      if (this.persistentTemplatePart(templatePartName) && window.templatePartsLoader.isRunning(templatePartName)) {
        reject(new Error('Not re-creating persistent templatePart'))
      }
      try {
        // Create a new class instance from the templateParts container and call init()
        const templatePartIndex = window.templatePartsLoader.templateParts.findIndex(templatePart => templatePart.templatePartName === templatePartName)
        if (templatePartIndex !== -1) {
          const newInstance = Object.create(Object.getPrototypeOf(window.templatePartsLoader.templateParts[templatePartIndex]['templatePartClass']))
          newInstance.constructor.apply(newInstance)
          newInstance.init()
          window.templatePartsLoader.runningTemplateParts.push({ templatePartName: templatePartName, templatePartInstance: newInstance })
          resolve(templatePartName)
        }
        reject(new Error(`Could not find templatePart: ${templatePartName}`))
      } catch (e) {
        console.log('Could not init:', templatePartName)
        reject(e)
      }
    })
  }

  /**
   * Returns templatePart settings for persistence
   * @param templatePartName
   * @returns {boolean}
   */
  persistentTemplatePart (templatePartName) {
    const templatePartIndex = window.templatePartsLoader.templateParts.findIndex(templatePart => templatePart.templatePartName === templatePartName)
    if (templatePartIndex === -1) return false
    const templatePartSettings = window.templatePartsLoader.templateParts[templatePartIndex].templatePartSettings
    return 'persistent' in templatePartSettings && templatePartSettings.persistent === true
  }

  /**
   * Whether templatePart is running or not
   * @param templatePartName
   * @returns {boolean}
   */
  isRunning (templatePartName) {
    return !!window.templatePartsLoader.runningTemplateParts.find(templatePart => templatePart.templatePartName === templatePartName)
  }

  /**
   * Calls destroy on all running templateParts and removes them from this.runningTemplateParts
   * @returns {Promise<any[] | never>}
   */
  destroyTemplateParts () {
    const promises = []
    for (const templatePart of window.templatePartsLoader.runningTemplateParts) {
      // Don't destroy templatePart if it is persistent type
      if (window.templatePartsLoader.persistentTemplatePart(templatePart.templatePartName) === false) {
        promises.push(window.templatePartsLoader.destroyTemplatePartInstance(templatePart.templatePartName))
      }
    }
    return Promise.all(promises).then((res) => {
      // console.log('Destroyed:', res.join(', '))
    }).catch(e => {
      console.warn(e)
    })
  }

  /**
   * Takes an array of templatePartNames and initialises them
   * @param templateParts
   * @returns {Promise<any[] | never>}
   */
  initTemplateParts (templateParts) {
    // console.log(templateParts)
    const promises = []
    for (const templatePartName of templateParts) {
      // Don't init templatePart if its persistent and already running
      if (window.templatePartsLoader.persistentTemplatePart(templatePartName) && window.templatePartsLoader.isRunning(templatePartName) === false) {
        promises.push(window.templatePartsLoader.initTemplatePartInstance(templatePartName))
      }
      // Non-persistent templateParts can be initialised
      if (window.templatePartsLoader.persistentTemplatePart(templatePartName) === false) {
        promises.push(window.templatePartsLoader.initTemplatePartInstance(templatePartName))
      }
    }
    return Promise.all(promises).then((res) => {
      // console.log('Initialised:', res.join(', '))
    }).catch(e => {
      console.warn(e)
    })
  }

  /**
   * Fetches the templateParts from the dom
   * @param {String|Node|Document} htmlString or document
   */
  fetchTemplatePartsFromHtml (htmlString) {
    if (typeof htmlString === 'object') {
      htmlString = new window.XMLSerializer().serializeToString(htmlString)
    }
    const html = new window.DOMParser().parseFromString(htmlString, 'text/html')
    if ('body' in html === false || html.body.childNodes.length < 1) return
    const templatePartsList = html.querySelector('.data')
    if (templatePartsList !== null) {
      const templateParts = templatePartsList.dataset.templateParts.split(',')
      const templatePartNames = templateParts.map(templatePart => upperFirst(camelCase(templatePart)))
      window.templatePartsLoader.destroyTemplateParts().then(() => {
        window.templatePartsLoader.initTemplateParts(templatePartNames).then(() => {
          // All templateParts initialised
        })
      })
    }
  }
}

export default TemplatePartsLoader
