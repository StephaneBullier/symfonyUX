import React from 'react';

export default class MadeWithLove extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      hearts: 1,
    };
  }

  render() {
    return (
      <div className="container">
        <span>Fabriqué avec </span>
        <span
          onClick={() => this.setState({
            hearts: this.state.hearts + 1,
          })}
        >
          {'❤'.repeat(this.state.hearts)}
        </span>
        <span> par vos amis de </span>
        <a href="https://symfonycasts.com" target="_blank">
          SymfonyCasts
        </a>
      </div>
    )
  }
}
