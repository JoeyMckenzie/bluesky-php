# TODO

Endpoints still to implement for client

## Actor

- [x] `app.bsky.actor.getPreferences`
- [x] `app.bsky.actor.getProfile`
- [x] `app.bsky.actor.getProfiles`
- [x] `app.bsky.actor.getSuggestions`
- [x] `app.bsky.actor.putPreferences`
- [x] `app.bsky.actor.searchActorsTypeahead`
- [x] `app.bsky.actor.searchActors`

## Feed

- [ ] `app.bsky.feed.describeFeedGenerator` ⚠️(not working)
- [ ] `app.bsky.feed.getActorFeeds` ⚠️(need some feeds to test with)
- [x] `app.bsky.feed.getActorLikes`
- [x] `app.bsky.feed.getAuthorFeed`
- [x] `app.bsky.feed.getFeedGenerator`
- [x] `app.bsky.feed.getFeedGenerators`
- [ ] `app.bsky.feed.getFeedSkeleton` ⚠️(need an example call)
- [x] `app.bsky.feed.getFeed`
- [x] `app.bsky.feed.getLikes`
- [x] `app.bsky.feed.getListFeed`
- [x] `app.bsky.feed.getPostThread`
- [x] `app.bsky.feed.getPosts`
- [x] `app.bsky.feed.getQuotes`
- [x] `app.bsky.feed.getRepostedBy`
- [x] `app.bsky.feed.getSuggestedFeeds`
- [x] `app.bsky.feed.getTimeline`
- [x] `app.bsky.feed.searchPosts`
- [ ] `app.bsky.feed.sendInteractions` ⚠️(getting XRPCNotSupported error)

## Graph

- [x] `app.bsky.graph.getActorStarterPacks`
- [x] `app.bsky.graph.getBlocks`
- [x] `app.bsky.graph.getFollowers`
- [x] `app.bsky.graph.getFollows`
- [x] `app.bsky.graph.getKnownFollowers`
- [ ] `app.bsky.graph.getListBlocks` ⚠️(need an example)
- [ ] `app.bsky.graph.getListMutes` ⚠️(need an example)
- [ ] `app.bsky.graph.getList`
- [ ] `app.bsky.graph.getLists`
- [ ] `app.bsky.graph.getMutes`
- [ ] `app.bsky.graph.getRelationships`
- [ ] `app.bsky.graph.getStarterPack`
- [ ] `app.bsky.graph.getStarterPacks`
- [ ] `app.bsky.graph.getSuggestedFollowsByActor`
- [ ] `app.bsky.graph.muteActorList`
- [ ] `app.bsky.graph.muteActor`
- [ ] `app.bsky.graph.muteThread`
- [ ] `app.bsky.graph.unmuteActorList`
- [ ] `app.bsky.graph.unmuteActor`
- [ ] `app.bsky.graph.unmuteThread`

## Labeler

- [ ] `app.bsky.labeler.getServices`

## Notification

- [x] `app.bsky.notification.getUnreadCount`
- [ ] `app.bsky.notification.listNotifications`
- [ ] `app.bsky.notification.putPreferences`
- [ ] `app.bsky.notification.registerPush`
- [ ] `app.bsky.notification.updateSeen`

## Video

- [ ] `app.bsky.video.getJobStatus`
- [ ] `app.bsky.video.getUploadLimits`
- [ ] `app.bsky.video.uploadVideo`

## Chat

- [ ] `chat.bsky.actor.deleteAccount`
- [ ] `chat.bsky.actor.exportAccountData`
- [ ] `chat.bsky.convo.deleteMessageForSelf`
- [ ] `chat.bsky.convo.getConvoForMembers`
- [ ] `chat.bsky.convo.getConvo`
- [ ] `chat.bsky.convo.getLog`
- [ ] `chat.bsky.convo.getMessages`
- [ ] `chat.bsky.convo.leaveConvo`
- [ ] `chat.bsky.convo.listConvos`
- [ ] `chat.bsky.convo.muteConvo`
- [ ] `chat.bsky.convo.sendMessageBatch`
- [ ] `chat.bsky.convo.sendMessage`
- [ ] `chat.bsky.convo.unmuteConvo`
- [ ] `chat.bsky.convo.updateRead`
- [ ] `chat.bsky.moderation.getActorMetadata`
- [ ] `chat.bsky.moderation.getMessageContext`
- [ ] `chat.bsky.moderation.updateActorAccess`
- [ ] `com.atproto.admin.deleteAccount`
- [ ] `com.atproto.admin.disableAccountInvites`
- [ ] `com.atproto.admin.disableInviteCodes`
- [ ] `com.atproto.admin.enableAccountInvites`
- [ ] `com.atproto.admin.getAccountInfo`
- [ ] `com.atproto.admin.getAccountInfos`
- [ ] `com.atproto.admin.getInviteCodes`
- [ ] `com.atproto.admin.getSubjectStatus`
- [ ] `com.atproto.admin.searchAccounts`
- [ ] `com.atproto.admin.sendEmail`
- [ ] `com.atproto.admin.updateAccountEmail`
- [ ] `com.atproto.admin.updateAccountHandle`
- [ ] `com.atproto.admin.updateAccountPassword`
- [ ] `com.atproto.admin.updateSubjectStatus`
- [ ] `com.atproto.identity.getRecommendedDidCredentials`
- [ ] `com.atproto.identity.requestPlcOperationSignature`
- [ ] `com.atproto.identity.resolveHandle`
- [ ] `com.atproto.identity.signPlcOperation`
- [ ] `com.atproto.identity.submitPlcOperation`
- [ ] `com.atproto.identity.updateHandle`
- [ ] `com.atproto.label.queryLabels`
- [ ] `com.atproto.moderation.createReport`
- [ ] `com.atproto.repo.applyWrites`
- [ ] `com.atproto.repo.createRecord`
- [ ] `com.atproto.repo.deleteRecord`
- [ ] `com.atproto.repo.describeRepo`
- [ ] `com.atproto.repo.getRecord`
- [ ] `com.atproto.repo.importRepo`
- [ ] `com.atproto.repo.listMissingBlobs`
- [ ] `com.atproto.repo.listRecords`
- [ ] `com.atproto.repo.putRecord`
- [ ] `com.atproto.repo.uploadBlob`
- [ ] `com.atproto.server.activateAccount`
- [ ] `com.atproto.server.checkAccountStatus`
- [ ] `com.atproto.server.confirmEmail`
- [ ] `com.atproto.server.createAccount`
- [ ] `com.atproto.server.createAppPassword`
- [ ] `com.atproto.server.createInviteCode`
- [ ] `com.atproto.server.createInviteCodes`
- [x] `com.atproto.server.createSession`
- [ ] `com.atproto.server.deactivateAccount`
- [ ] `com.atproto.server.deleteAccount`
- [ ] `com.atproto.server.deleteSession`
- [ ] `com.atproto.server.describeServer`
- [ ] `com.atproto.server.getAccountInviteCodes`
- [ ] `com.atproto.server.getServiceAuth`
- [ ] `com.atproto.server.getSession`
- [ ] `com.atproto.server.listAppPasswords`
- [x] `com.atproto.server.refreshSession`
- [ ] `com.atproto.server.requestAccountDelete`
- [ ] `com.atproto.server.requestEmailConfirmation`
- [ ] `com.atproto.server.requestEmailUpdate`
- [ ] `com.atproto.server.requestPasswordReset`
- [ ] `com.atproto.server.reserveSigningKey`
- [ ] `com.atproto.server.resetPassword`
- [ ] `com.atproto.server.revokeAppPassword`
- [ ] `com.atproto.server.updateEmail`
- [ ] `com.atproto.sync.getBlob`
- [ ] `com.atproto.sync.getBlocks`
- [ ] `com.atproto.sync.getLatestCommit`
- [ ] `com.atproto.sync.getRecord`
- [ ] `com.atproto.sync.getRepoStatus`
- [ ] `com.atproto.sync.getRepo`
- [ ] `com.atproto.sync.listBlobs`
- [ ] `com.atproto.sync.listRepos`
- [ ] `com.atproto.sync.notifyOfUpdate`
- [ ] `com.atproto.sync.requestCrawl`
- [ ] `tools.ozone.communication.createTemplate`
- [ ] `tools.ozone.communication.deleteTemplate`
- [ ] `tools.ozone.communication.listTemplates`
- [ ] `tools.ozone.communication.updateTemplate`
- [ ] `tools.ozone.moderation.emitEvent`
- [ ] `tools.ozone.moderation.getEvent`
- [ ] `tools.ozone.moderation.getRecord`
- [ ] `tools.ozone.moderation.getRecords`
- [ ] `tools.ozone.moderation.getRepo`
- [ ] `tools.ozone.moderation.getRepos`
- [ ] `tools.ozone.moderation.queryEvents`
- [ ] `tools.ozone.moderation.queryStatuses`
- [ ] `tools.ozone.moderation.searchRepos`
- [ ] `tools.ozone.server.getConfig`
- [ ] `tools.ozone.set.addValues`
- [ ] `tools.ozone.set.deleteSet`
- [ ] `tools.ozone.set.deleteValues`
- [ ] `tools.ozone.set.getValues`
- [ ] `tools.ozone.set.querySets`
- [ ] `tools.ozone.set.upsertSet`
- [ ] `tools.ozone.signature.findCorrelation`
- [ ] `tools.ozone.signature.findRelatedAccounts`
- [ ] `tools.ozone.signature.searchAccounts`
- [ ] `tools.ozone.team.addMember`
- [ ] `tools.ozone.team.deleteMember`
- [ ] `tools.ozone.team.listMembers`
- [ ] `tools.ozone.team.updateMember`
