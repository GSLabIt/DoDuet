# Referral module

This module is responsible for the handling of referral codes and invitation.

Referral redemption handling is done via the event `ReferralRedeemed` fired once an user redeems the
prize for one or more referrals.

The event is fired once per redeem, multiple redeem will result in the event being fired multiple times

## Routes
All routes gets prefixed by `referral`

| Method | Name                                            | Callback                           | Route                   |
|--------|-------------------------------------------------|------------------------------------|-------------------------|
| `Get`  | `authenticated.referral.render.index`           | `ReferralController@index`         | `/`                     |
| `Get`  | `authenticated.referral.get.url`                | `ReferralController@url`           | `/url`                  |
| `Get`  | `authenticated.referral.get.new_ref_prize`      | `ReferralController@newRefPrize`   | `/prize`                |
| `Get`  | `authenticated.referral.get.total_ref_prize`    | `ReferralController@totalRefPrize` | `/total-prize`          |
|        |                                                 |                                    |                         |
| `Post` | `authenticated.referral.post.redeem_all_prizes` | `ReferralController@redeemAll`     | `/redeem`               |
| `Post` | `authenticated.referral.post.redeem_prize`      | `ReferralController@redeem`        | `/redeem/{referred_id}` |

## Views
Views are not compiled but only generated as a placeholder
