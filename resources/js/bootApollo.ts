import {ApolloClient, InMemoryCache, ApolloLink} from '@apollo/client/core'
import {createApolloProvider} from '@vue/apollo-option'
import { createUploadLink } from 'apollo-upload-client';

const httpLink = new createUploadLink({
    uri: 'http://127.0.0.1:8000/graphql',
    // uri: 'https://doduet.studio/graphql',
    headers: {
        "X-CSRF-TOKEN": document.head.querySelector("meta[name='xsrf']")?.getAttribute("content"),
        "Authorization": `Bearer ${document.head.querySelector("meta[name='bearer']")?.getAttribute("content")}`
    }
});

const cache = new InMemoryCache()
const apolloClient = new ApolloClient({
    cache,
    link: httpLink,
})

const apolloProvider = createApolloProvider({
    defaultClient: apolloClient,
})

export default apolloProvider
