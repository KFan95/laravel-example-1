import React, {Component} from 'react';
import VideoUpload from "./VideoUpload";
import VideoListItem from './VideoListItem';
import { Link } from "react-router-dom";
import axios from 'axios';

export default class VideoList extends Component {
    constructor(props) {
        super(props);

        this.mounted = false;

        this.state = {
            videos: []
        };

        this.videoUploaded = this.videoUploaded.bind(this);
    }

    componentDidMount() {
        this.mounted = true;

        axios.get(document.location.href, {
            responseType: 'json'
        })
            .then((response) => response.data)
            .then((data) => {
                this.setState({
                    videos: data.videos
                });
            })
            .catch(() => {
                alert('Error loading video data');
            })
            .then(() => {
                // loading end
            });
    }

    componentWillUnmount() {
        this.mounted = false;
    }

    videoUploaded(video) {
        this.setState({
            videos: [
                video,
                ...this.state.videos
            ]
        })
    }

    render() {
        return (
            <main className="p-4">
                <div className="d-flex flex-row">
                    <div className="d-flex flex-fill justify-content-start pl-2">
                        Все видео
                    </div>
                    <div className="d-flex flex-fill justify-content-end pr-2">
                        <VideoUpload onVideoUpload={this.videoUploaded}/>
                    </div>
                </div>
                <div className="d-flex flex-wrap">
                    {this.state.videos.map((item) => {
                        return <VideoListItem key={item.id} item={item} />;
                    })}
                </div>
            </main>
        );
    }
}
