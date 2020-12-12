import { Controller } from 'stimulus'

export default class extends Controller {
  static values = { url: String }
  static targets = ['posts']

  connect() {
    this.loadPost()
  }

  loadPost() {
    fetch(this.urlValue)
      .then(response => response.json())
      .then(posts => {
        let postsHTML = ''
        posts.forEach(post => postsHTML += this.postTemplate(post))
        this.postsTarget.innerHTML = postsHTML
      })
  }

  postTemplate(post) {
    return `<li>${post.title}</li>`
  }
}


