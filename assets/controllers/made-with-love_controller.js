import { Controller } from 'stimulus';
import React from 'react';
import MadeWithLove from '../components/MadeWithLove';

export default class extends Controller {
  connect() {
    import('react-dom').then((ReactDOM) => {
      ReactDOM.render(
        <MadeWithLove />,
        this.element
      )
    }).default
  }
}
