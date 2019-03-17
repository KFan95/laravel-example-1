import React, {Component} from 'react';
import axios from 'axios';

export default class VideoUpload extends Component {
    constructor(props) {
        super(props);

        this.state = {
            upload: false
        };

        this.uploadFileChange = this.uploadFileChange.bind(this);
    }


    uploadFileChange(e) {
        let file = e.target.files;

        if (file.length > 0) {
            this.uploadFile(e.target.files[0]);
        }

        e.target.value = '';
    }

    uploadFile(file) {
        this.setState({
            upload: true
        });

        var data = new FormData();
        data.append('file', file);

        axios.post('/video/upload', data, {
            onUploadProgress: function (progressEvent) {
                //console.log(progressEvent);
            }
        })
            .then((response) => response.data)
            .then((data) => {
                if (data.result) {
                    this.props.onVideoUpload(data.video);
                }
            })
            .catch(() => {
                // error
            })
            .then(() => {
                this.setState({
                    upload: false
                });
            });
    }

    render() {
        return (
            <label htmlFor="uploadFile">
                <span className={"btn " + (this.state.upload ? "btn-secondary" : "btn-primary")}>{this.state.upload ? 'Загрузка' : 'Загрузить'}</span>
                <input onChange={this.uploadFileChange} id="uploadFile" type="file" disabled={this.state.upload} hidden />
            </label>
        );
    }
}
