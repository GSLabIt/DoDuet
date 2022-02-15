import gql from "graphql-tag";

///////////////////
//               //
//    QUERIES    //
//               //
///////////////////

export const GET_SERVER_PUBLIC_KEY = gql`
    query {
        getServerPublicKey
    }
`

export const GET_USER_SECRET_KEY = gql`
    query {
        getUserSecretKey
    }
`

export const GET_USER_PUBLIC_KEY = gql`
    query (
        $id: ID!
    ) {
        getUserPublicKey(
            id: $id
        )
    }
`
