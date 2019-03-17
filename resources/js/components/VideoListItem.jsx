import React, {Component} from 'react';
import axios from 'axios';
import {Link} from "react-router-dom";

export default class VideoListItem extends Component {
    constructor(props) {
        super(props);

        this.mounted = false;
        this.stateTimeout = false;

        this.state = {
            item: props.item
        };

        this.refreshState = this.refreshState.bind(this);
    }

    componentDidMount() {
        this.mounted = true;

        if (this.props.item.status === 'process') {
            this.stateTimeout = setTimeout(this.refreshState, 1000);
        }
    }

    componentWillUnmount() {
        this.mounted = false;
    }

    refreshState() {
        const {id} = this.state.item;
        let {status} = this.state.item;

        axios.get('/video/' + id + '/state')
            .then((response) => response.data)
            .then((data) => {
                if (data.status !== 'process' && this.mounted) {
                    this.setState({
                        item: {
                            ...this.props.item,
                            ...data.video
                        }
                    });

                    status = data.status;
                }
            })
            .catch(() => {
                alert('Error loading video data');
            })
            .then(() => {
                if (status === 'process' && this.mounted) {
                    this.stateTimeout = setTimeout(this.refreshState, 1000);
                }
            });
    }

    render() {
        const {id, status, name, preview, duration} = this.state.item;

        return (
            <div className="video-item p-3">
                <Link to={"/video/" + id}>
                    <div className="video-image-wrap">
                        {status === 'processed' && <div className="video-image" style={{
                            backgroundImage: 'url(/storage/' + preview + ')',
                            backgroundRepeat: 'no-repeat',
                            backgroundPosition: 'center center',
                            backgroundSize: 'contain'
                        }}/>}
                        {status !== 'processed' && <div className="video-image" style={{
                            backgroundImage: 'url(https://media.giphy.com/media/y1ZBcOGOOtlpC/200.gif)',
                            backgroundRepeat: 'no-repeat',
                            backgroundPosition: 'center center',
                            backgroundSize: 'contain'
                        }}/>}
                    </div>
                    <div className="video-name">
                        {name}{duration ? ' (' + duration + 's)' : ''}
                    </div>
                </Link>
            </div>
        );
    }
}
