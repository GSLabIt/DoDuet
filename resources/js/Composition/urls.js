export default function globalUrls() {
    const routeMatch = (test_route) => {
        return route().current() === test_route || location.href.endsWith(test_route) // to support indexed routes
    }

    const classIfRouteMatch = (test_route) => {
        return {
            "font-semibold": routeMatch(test_route)
        }
    }

    return {
        urls: {
            home: `${route("home")}/#home`,
            login: route("login"),
            register: route("register"),
            dashboard: route("dashboard"),
            how_it_works: `${route("home")}/#how-it-works`,
            plans: `${route("home")}/#plans`,
            tracks: `${route("home")}/#tracks`,

            terms_of_service: route("home"),
            refund_policy: route("home"),
            privacy_policy: route("home"),
            cookie_policy: route("home"),
            faq: route("home"),
            support: route("home"),
            sitemap: route("home"),
        },
        routeMatch,
        classIfRouteMatch,
    }
}

function urlGenerator(prefix, functionalities = {}) {
    functionalities = {
        create: true,
        read: true,
        ...functionalities
    }
    let result = {}

    if(functionalities.read) {
        result = {
            ...result,
            index: route(`${prefix}-index`),
        }
    }

    if(functionalities.create) {
        result = {
            ...result,
            create: route(`${prefix}-create`),
            store: route(`${prefix}-store`),
        }
    }

    return {
        [prefix]: result
    }
}

export function adminUrls() {
    const {routeMatch, classIfRouteMatch} = globalUrls()

    return {
        urls: {
            global: {
                ...globalUrls().urls
            },
            /*...urlGenerator("category"),
            ...urlGenerator("plan"),
            ...urlGenerator("plan_feature"),
            ...urlGenerator("platform_functionality"),*/
        },
        routeMatch,
        classIfRouteMatch
    }
}
