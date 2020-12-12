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
    let postsHTML = ''
    await postsArray.forEach(post => postsHTML += `<li>${post.title}</li>`)
    this.postsTarget.innerHTML = postsHTML
  }
}


