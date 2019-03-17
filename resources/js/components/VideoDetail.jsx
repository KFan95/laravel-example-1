import React, {Component} from 'react';
import axios from 'axios';
import {Link} from "react-router-dom";

export default class VideoDetail extends Component {
    constructor(props) {
        super(props);

        this.mounted = false;

        this.state = {
            video: false
        }
    }

    componentDidMount() {
        this.mounted = true;

        axios.get(document.location.href, {
            responseType: 'json'
        })
            .then((response) => response.data)
            .then((data) => {
                this.setState({
                    video: data.video
                });
            })
            .catch(() => {
                alert('Error loading video data');
            });
    }

    componentWillMount() {
        this.mounted = false;
    }

    render() {
        const video = this.state.video ? this.state.video : false;

        return (
            <main className="p-4">
                <div className="d-flex flex-row">
                    <div className="d-flex flex-fill justify-content-start pl-2">
                        <Link to={"/"}>Вернуться в список</Link>
                    </div>
                </div>

                {video && <video className="mt-2" controls>
                    <source src={"/storage/" + video.src} type="video/mp4"/>
                </video>}
            </main>
    );
    }
    }
