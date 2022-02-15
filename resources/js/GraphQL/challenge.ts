import gql from "graphql-tag";

///////////////////
//               //
//    QUERIES    //
//               //
///////////////////

export const GET_ALL_TRACKS_IN_LATEST_CHALLENGE = gql`
    query {
        getAllTracksInLatestChallenge
    }
`

export const GET_ALL_TRACKS_IN_CHALLENGE = gql`
    query (
        $challenge_id: ID!
    ) {
        getAllTracksInChallenge(
            challenge_id: $challenge_id
        )
    }
`

export const GET_ALL_USER_PRIZES = gql`
    query {
        getAllUserPrizes
    }
`

export const GET_NUMBER_OF_PARTICIPATING_TRACKS = gql`
    query {
        getNumberOfParticipatingTracks
    }
`

export const GET_AVERAGE_VOTE_IN_CHALLENGE_OF_TRACK = gql`
    query (
        $track_id: ID!
        $challenge_id: ID
    ) {
        getAverageVoteInChallengeOfTrack(
            track_id: $track_id
            challenge_id: $challenge_id
        )
    }
`

export const GET_NUMBER_OF_LISTENING_IN_CHALLENGE = gql`
    query (
        $track_id: ID!
        $challenge_id: ID
    ) {
        getNumberOfListeningInChallenge(
            track_id: $track_id
            challenge_id: $challenge_id
        )
    }
`

export const GET_NUMBER_OF_PARTICIPATING_USERS = gql`
    query {
        getNumberOfParticipatingUsers
    }
`

export const GET_TRACK_VOTE_BY_USER_AND_CHALLENGE = gql`
    query (
        $track_id: ID!
        $user_id: ID
        $challenge_id: ID
    ) {
        getTrackVoteByUserAndChallenge(
            track_id: $track_id
            user_id: $user_id
            challenge_id: $challenge_id
        )
    }
`

export const GET_NUMBER_OF_TRACK_LISTENING_BY_USER_AND_CHALLENGE = gql`
    query (
        $track_id: ID!
        $user_id: ID
        $challenge_id: ID
    ) {
        getNumberOfTrackListeningByUserAndChallenge(
            track_id: $track_id
            user_id: $user_id
            challenge_id: $challenge_id
        )
    }
`

export const GET_TOTAL_AVERAGE_TRACK_VOTE = gql`
    query (
        $track_id: ID!
    ) {
        getTotalAverageTrackVote(
            track_id: $track_id
        )
    }
`

export const GET_NUMBER_OF_TOTAL_LISTENING_BY_TRACK = gql`
    query (
        $track_id: ID!
    ) {
        getNumberOfTotalListeningByTrack(
            track_id: $track_id
        )
    }
`
