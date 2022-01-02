export default function eventSubscribers(getter) {
    const subscribeICOEvents = (web3, buy_function = (err, res) => {}, finalize_function = (err, res) => {}, redeem_function = (err, res) => {}) => {
        const ico = getter.getICOContract(web3)
        ico.events.Buy({}, buy_function)
        ico.events.Finalize({}, finalize_function)
        ico.events.Redeemed({}, redeem_function)
    }

    const subscribeTrackRegistrationEvent = (web3, fn) => {
        const track = getter.getTrackContract(web3)

        track.events.TrackRegistered({}, fn)
    }

    const subscribeChallengeParticipationEvent = (web3, fn) => {
        const challenge = getter.getChallengeContract(web3)

        challenge.events.NewParticipant({}, fn)
    }

    const subscribeChallengeVoteEvent = (web3, fn) => {
        const challenge = getter.getChallengeContract(web3)

        challenge.events.Voted({}, fn)
    }

    return {
        subscribeICOEvents,
        subscribeTrackRegistrationEvent,
        subscribeChallengeParticipationEvent,
        subscribeChallengeVoteEvent,
    }
}
