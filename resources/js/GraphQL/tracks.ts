import gql from "graphql-tag";

///////////////////
//               //
//   MUTATIONS   //
//               //
///////////////////

export const CREATE_TRACK = gql`
    mutation (
        $name: String!
        $description: String!
        $duration: String!
        $file: Upload!
        $cover: ID
        $lyric: ID
        $album: ID
    ) {
        createTrack(input: {
            album: $album
            cover: $cover
            lyric: $lyric
            name: $name
            description: $description
            duration: $duration
            mp3: $file
        }), {
            id
        }
    }
`

export const UPDATE_TRACK = gql`
    mutation (
        $id: ID!
        $name: String!
        $description: String!
        $cover: ID
        $lyric: ID
        $album: ID
    ) {
        updateTrack(input: {
            id: $id
            album: $album
            cover: $cover
            lyric: $lyric
            name: $name
            description: $description
        }), {
            id
        }
    }
`

export const LINK_TO_ALBUM = gql`
    mutation (
        $album_id: ID!,
        $track_id: ID!
    ) {
        linkToAlbum(
            album_id: $album_id
            track_id: $track_id
        )
    }
`

export const LINK_TO_COVER = gql`
    mutation (
        $cover_id: ID!,
        $track_id: ID!
    ) {
        linkToCover(
            cover_id: $cover_id
            track_id: $track_id
        )
    }
`

export const LINK_TO_LYRIC = gql`
    mutation (
        $lyric_id: ID!,
        $track_id: ID!
    ) {
        linkToLyric(
            lyric_id: $lyric_id
            track_id: $track_id
        )
    }
`

///////////////////
//               //
//    QUERIES    //
//               //
///////////////////

export const GET_TOTAL_VOTES = gql`
    query (
        $track_id: ID!
    ) {
        getTotalVotes(
            track_id: $track_id
        )
    }
`

export const GET_AVERAGE_VOTE = gql`
    query (
        $track_id: ID!
    ) {
        getAverageVote(
            track_id: $track_id
        )
    }
`

export const GET_TOTAL_LISTENINGS = gql`
    query (
        $track_id: ID!
    ) {
        getTotalListenings(
            track_id: $track_id
        )
    }
`

export const GET_USER_TRACKS = gql`
    query (
        $user_id: ID!
    ) {
        getUsersTracks(
            user_id: $user_id
        )
    }
`

export const GET_MOST_VOTED_TRACKS = gql`
    query {
        getMostVotedTracks
    }
`

export const GET_MOST_LISTENED_TRACKS = gql`
    query {
        getMostListenedTracks
    }
`

export const GET_NOT_IN_CHALLENGE_TRACKS = gql`
    query {
        getNotInChallengeTracks
    }
`

export const GET_TRACK_LINK = gql`
    query (
        $track_id: ID!
    ) {
        getTrackLink(
            track_id: $track_id
        )
    }
`
