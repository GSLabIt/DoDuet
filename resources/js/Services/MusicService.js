/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

import {SignalDispatcher} from "strongly-typed-events"

export class MusicService {

    playableSongs = [] /*[{id, name, author, lenght}]*/
    playingSong = {} /*{id, name, author, lenght}*/
    isPlaying = false
    votingTime = false
    _onChange = new SignalDispatcher()

    constructor() {
        //singleton
        if (MusicService._instance) {
            return MusicService._instance
        }
        MusicService._instance = this
    }

    createState(){
        return {
            playableSongs: this.playableSongs,
            playingSong: this.playingSong,
            isPlaying: this.isPlaying,
            votingTime: this.votingTime
        }
    }

    onChangeDispatch(){
        this._onChange.dispatchAsync(this.createState())
    }

    get onChange() {
        return this._onChange.asEvent();
    }

    setPlayableSongs(value) {
        this.playableSongs = value
        this.onChangeDispatch()
    }

    setPlayingSong(value) {
        this.playingSong = value
        this.onChangeDispatch()
    }

    setIsPlaying(value) {
        this.isPlaying = value
        this.onChangeDispatch()
    }

    set votingTime(value) {
        this.votingTime = value
        this.onChangeDispatch()
    }
}

