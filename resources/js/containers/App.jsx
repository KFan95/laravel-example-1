import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route } from "react-router-dom";

import VideoList from '../components/VideoList';
import VideoDetail from '../components/VideoDetail';

export default class App extends Component {
    render() {
        return (
            <Router>
                <Route path="/" exact component={VideoList} />
                <Route path="/video/:id" component={VideoDetail} />
            </Router>
        );
    }
}

if (document.getElementById('app')) {
    ReactDOM.render(<App />, document.getElementById('app'));
}
