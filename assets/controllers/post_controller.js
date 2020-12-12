import { Controller } from 'stimulus'

export default class extends Controller {
  static values = { url: String }
  static targets = ['posts']

  connect() {
    this.loadPost()
  }

  async loadPost() {
    const response = await fetch(this.urlValue)
    const postsArray = await response.json()
    let postsHTML = []
    postsArray.forEach(post => postsHTML += this.displayTitle(post))
    this.postsTarget.innerHTML = postsHTML
  }

  displayTitle(post) {
    return `<li>${post.title}</li>`
  }
}


